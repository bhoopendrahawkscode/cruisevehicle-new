<?php

namespace App\Console\Commands;

use App\Traits\GenerateMigrationTable;
use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use KitLoong\MigrationsGenerator\Enum\Driver;
use KitLoong\MigrationsGenerator\Migration\ForeignKeyMigration;
use KitLoong\MigrationsGenerator\Migration\ProcedureMigration;
use KitLoong\MigrationsGenerator\Migration\Squash;
use KitLoong\MigrationsGenerator\Migration\TableMigration;
use KitLoong\MigrationsGenerator\Migration\ViewMigration;
use KitLoong\MigrationsGenerator\Schema\Models\Procedure;
use KitLoong\MigrationsGenerator\Schema\Models\View;
use KitLoong\MigrationsGenerator\Schema\MySQLSchema;
use KitLoong\MigrationsGenerator\Schema\PgSQLSchema;
use KitLoong\MigrationsGenerator\Schema\Schema;
use KitLoong\MigrationsGenerator\Schema\SQLiteSchema;
use KitLoong\MigrationsGenerator\Schema\SQLSrvSchema;
use KitLoong\MigrationsGenerator\Setting;

class MigrateCommand extends Command
{
    const PREPARED = "Prepared";
    const CREATED = "Created";


    use GenerateMigrationTable;
    protected $signature = 'mig:generate
                            {tables? : A list of tables or views you wish to generate migrations for separated by a comma: users,posts,comments}
                            {--c|connection= : The database connection to use}
                            {--t|tables= : A list of tables or views you wish to generate migrations for separated by a comma: users,posts,comments}
                            {--i|ignore= : A list of tables or views you wish to ignore, separated by a comma: users,posts,comments}
                            {--p|path= : Where should the file be created?}
                            {--tp|template-path= : The location of the template for this generator}
                            {--date= : Migrations will be created with specified date. Views and foreign keys will be created with + 1 second. Date should be in format supported by Carbon::parse}
                            {--table-filename= : Define table migration filename, default pattern: [datetime]_create_[name]_table.php}
                            {--view-filename= : Define view migration filename, default pattern: [datetime]_create_[name]_view.php}
                            {--proc-filename= : Define stored procedure migration filename, default pattern: [datetime]_create_[name]_proc.php}
                            {--fk-filename= : Define foreign key migration filename, default pattern: [datetime]_add_foreign_keys_to_[name]_table.php}
                            {--log-with-batch= : Log migrations with given batch number. We recommend using batch number 0 so that it becomes the first migration}
                            {--default-index-names : Don\'t use DB index names for migrations}
                            {--default-fk-names : Don\'t use DB foreign key names for migrations}
                            {--use-db-collation : Generate migrations with existing DB collation}
                            {--skip-log : Don\'t log into migrations table}
                            {--skip-vendor : Don\'t generate vendor migrations}
                            {--skip-views : Don\'t generate views}
                            {--skip-proc : Don\'t generate stored procedures}
                            {--squash : Generate all migrations into a single file}
                            {--with-has-table : Check for the existence of a table using `hasTable`}';


    protected $description = 'Generate migrations from an existing table structure.';

    protected Schema $schema;


    protected bool $shouldLog = false;

    protected int $nextBatchNumber = 0;

    public function __construct(
        protected MigrationRepositoryInterface $repository,
        protected Squash $squash,
        protected ForeignKeyMigration $foreignKeyMigration,
        protected ProcedureMigration $procedureMigration,
        protected TableMigration $tableMigration,
        protected ViewMigration $viewMigration,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $previousConnection = DB::getDefaultConnection();

        try {
            $this->setup($previousConnection);

            $connection = $this->option('connection') ?: $previousConnection;

            DB::setDefaultConnection($connection);

            $this->schema = $this->makeSchema();

            $this->info('Using connection: ' . $connection . "\n");

            $tables       = $this->filterTables()->sort()->values();
            $views        = $this->filterViews()->sort()->values();
            $generateList = $tables->merge($views)->unique();

            $this->info('Generating migrations for: ' . $generateList->implode(',') . "\n");

            $this->askIfLogMigrationTable($previousConnection);

            $this->generate($tables, $views);

            $this->info("\nFinished!\n");

            if (DB::getDriverName() === Driver::SQLITE->value) {
                $this->warn('SQLite only supports foreign keys upon creation of the table and not when tables are altered.');
                $this->warn('See https://www.sqlite.org/omitted.html');
                $this->warn('*_add_foreign_keys_* migrations were generated, however will get omitted if migrate to SQLite type database.');
            }
        } finally {
            DB::setDefaultConnection($previousConnection);
            app()->forgetInstance(Setting::class);
        }
    }


    protected function generateTablesToTemp(Collection $tables): void
    {
        $tables->each(function (string $table): void {
            $this->tableMigration->writeToTemp(
                $this->schema->getTable($table),
            );

            $this->info(self::PREPARED . " $table");
        });
    }

    protected function generateViews(Collection $views): void
    {
        $schemaViews = $this->schema->getViews();
        $schemaViews->each(function (View $view) use ($views): void {
            if (!$views->contains($view->getName())) {
                return;
            }

            $path = $this->viewMigration->write($view);

            $this->info(self::CREATED . " " . $path);

            if (!$this->shouldLog) {
                return;
            }

            $this->logMigration($path);
        });
    }

    protected function generateViewsToTemp(Collection $views): void
    {
        $schemaViews = $this->schema->getViews();
        $schemaViews->each(function (View $view) use ($views): void {
            if (!$views->contains($view->getName())) {
                return;
            }

            $this->viewMigration->writeToTemp($view);

            $this->info(self::PREPARED . " " . $view->getName());
        });
    }

    protected function generateProcedures(): void
    {
        $procedures = $this->schema->getProcedures();
        $procedures->each(function (Procedure $procedure): void {
            $path = $this->procedureMigration->write($procedure);

            $this->info(self::CREATED . " " . $path);


            if (!$this->shouldLog) {
                return;
            }

            $this->logMigration($path);
        });
    }

    protected function generateProceduresToTemp(): void
    {
        $procedures = $this->schema->getProcedures();
        $procedures->each(function (Procedure $procedure): void {
            $this->procedureMigration->writeToTemp($procedure);

            $this->info(self::PREPARED . " " . $procedure->getName());
        });
    }

    protected function generateForeignKeys(Collection $tables): void
    {
        $tables->each(function (string $table): void {
            $foreignKeys = $this->schema->getForeignKeys($table);

            if (!$foreignKeys->isNotEmpty()) {
                return;
            }

            $path = $this->foreignKeyMigration->write(
                $table,
                $foreignKeys,
            );

            $this->info(self::CREATED . " " . $path);


            if (!$this->shouldLog) {
                return;
            }

            $this->logMigration($path);
        });
    }

    protected function generateForeignKeysToTemp(Collection $tables): void
    {
        $tables->each(function (string $table): void {
            $foreignKeys = $this->schema->getForeignKeys($table);

            if (!$foreignKeys->isNotEmpty()) {
                return;
            }

            $this->foreignKeyMigration->writeToTemp(
                $table,
                $foreignKeys,
            );

            $this->info(self::PREPARED . " $table");
        });
    }

    protected function logMigration(string $migrationFilepath): void
    {
        $file = basename($migrationFilepath, '.php');
        $this->repository->log($file, $this->nextBatchNumber);
    }

    protected function makeSchema(): Schema
    {
        $driver = DB::getDriverName();
    
        if (!$driver) {
            throw new InvalidArgumentException('Failed to find database driver.');
        }
    
        $schemaClasses = [
            Driver::MYSQL->value => MySQLSchema::class,
            Driver::PGSQL->value => PgSQLSchema::class,
            Driver::SQLITE->value => SQLiteSchema::class,
            Driver::SQLSRV->value => SQLSrvSchema::class,
        ];
    
        if (isset($schemaClasses[$driver])) {
            return app($schemaClasses[$driver]);
        }
        throw new InvalidArgumentException('The database driver in use is not supported.');
    }
    
}
