<?php

namespace App\Traits;

use KitLoong\MigrationsGenerator\Setting;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use KitLoong\MigrationsGenerator\Migration\Migrator\Migrator;
use InvalidArgumentException;

trait GenerateMigrationTable
{


    protected function setup(string $connection): void
    {
        $setting = app(Setting::class);
        $setting->setDefaultConnection($connection);
        $setting->setUseDBCollation((bool) $this->option('use-db-collation'));
        $setting->setIgnoreIndexNames((bool) $this->option('default-index-names'));
        $setting->setIgnoreForeignKeyNames((bool) $this->option('default-fk-names'));
        $setting->setSquash((bool) $this->option('squash'));
        $setting->setWithHasTable((bool) $this->option('with-has-table'));

        $setting->setPath(
            $this->option('path') ?? Config::get('migrations-generator.migration_target_path'),
        );

        $this->setStubPath($setting);

        $setting->setDate(
            $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::now(),
        );

        $setting->setTableFilename(
            $this->option('table-filename') ?? Config::get('migrations-generator.filename_pattern.table'),
        );

        $setting->setViewFilename(
            $this->option('view-filename') ?? Config::get('migrations-generator.filename_pattern.view'),
        );

        $setting->setProcedureFilename(
            $this->option('proc-filename') ?? Config::get('migrations-generator.filename_pattern.procedure'),
        );

        $setting->setFkFilename(
            $this->option('fk-filename') ?? Config::get('migrations-generator.filename_pattern.foreign_key'),
        );
    }

    protected function setStubPath(Setting $setting): void
    {
        $defaultStub = Config::get('migrations-generator.migration_template_path');

        $setting->setStubPath(
            $this->option('template-path') ?? $defaultStub,
        );
    }

    protected function filterTables(): Collection
    {
        $tables = $this->schema->getTableNames();

        return $this->filterAndExcludeAsset($tables);
    }

    protected function filterViews(): Collection
    {
        if ($this->option('skip-views')) {
            return new Collection([]);
        }

        $views = $this->schema->getViewNames();

        return $this->filterAndExcludeAsset($views);
    }

    protected function filterAndExcludeAsset(Collection $allAssets): Collection
    {
        $tables = $allAssets;

        $tableArg = (string) $this->argument('tables');

        if ($tableArg !== '') {
            $tables = $allAssets->intersect(explode(',', $tableArg));
            return $tables->diff($this->getExcludedTables());
        }

        $tableOpt = (string) $this->option('tables');

        if ($tableOpt !== '') {
            $tables = $allAssets->intersect(explode(',', $tableOpt));
            return $tables->diff($this->getExcludedTables());
        }

        return $tables->diff($this->getExcludedTables());
    }

    protected function getExcludedTables(): array
    {
        $prefix = DB::getTablePrefix();


        $migrationTable = $prefix . (Config::get('database.migrations.table') ?? Config::get('database.migrations'));

        $excludes = [$migrationTable];
        $ignore   = (string) $this->option('ignore');

        if ($ignore !== '') {
            $excludes = array_merge($excludes, explode(',', $ignore));
        }

        if ($this->option('skip-vendor')) {
            $vendorTables = app(Migrator::class)->getVendorTableNames();
            $excludes     = array_merge($excludes, $vendorTables);
        }

        return $excludes;
    }

    protected function askIfLogMigrationTable(string $defaultConnection): void
    {
        if ($this->skipInput()) {
            return;
        }
    
        $this->shouldLog = $this->confirm('Do you want to log these migrations in the migrations table?', true);
    
        if (!$this->shouldLog) {
            return;
        }
    
        $this->repository->setSource(DB::getName());
    
        $logIntoCurrentConnection = DB::getName();
        $defaultConnectionName = $defaultConnection;
    
        if ($defaultConnection !== DB::getName() && !$this->confirm(
            "Log into current connection: $logIntoCurrentConnection? [Y = $logIntoCurrentConnection, n = $defaultConnectionName (default connection)]",
            true
        )) {
            $this->repository->setSource($defaultConnection);
        }
    
        if (!$this->repository->repositoryExists()) {
            $this->repository->createRepository();
        }
    
        $nextBatchNumberMessage = 'Next Batch Number is: ' . $this->repository->getNextBatchNumber() . '. We recommend using Batch Number 0 so that it becomes the "first" migration.';
        $this->nextBatchNumber = $this->askInt($nextBatchNumberMessage, 0);
    }
    

    protected function skipInput(): bool
    {
        if ($this->option('no-interaction') || $this->option('skip-log')) {
            return true;
        }

        if ($this->option('log-with-batch') === null) {
            return false;
        }

        if (!ctype_digit($this->option('log-with-batch'))) {
            throw new InvalidArgumentException('--log-with-batch must be a valid integer.');
        }

        $this->shouldLog       = true;
        $this->nextBatchNumber = (int) $this->option('log-with-batch');

        return true;
    }

    protected function askInt(string $question, ?int $default = null): int
    {
        $ask = 'Your answer needs to be a numeric value';

        if (!is_null($default)) {
            $question .= ' [Default: ' . $default . ']';
            $ask      .= ' or blank for default. [Default: ' . $default . ']';
        }

        $answer = $this->ask($question, (string) $default);

        while (!ctype_digit($answer) && !($answer === '' && !is_null($default))) {
            $answer = $this->ask($ask, (string) $default);
        }

        if ($answer === '') {
            $answer = $default;
        }

        return (int) $answer;
    }

    protected function generate(Collection $tables, Collection $views): void
    {
        if (app(Setting::class)->isSquash()) {
            $this->generateSquashedMigrations($tables, $views);
            return;
        }

        $this->generateMigrations($tables, $views);
    }

    protected function generateMigrations(Collection $tables, Collection $views): void
    {
        $setting = app(Setting::class);

        $this->info('Setting up Tables and Index migrations.');
        $this->generateTables($tables);

        if (!$this->option('skip-views')) {
            $setting->getDate()->addSecond();
            $this->info("\nSetting up Views migrations.");
            $this->generateViews($views);
        }

        if (!$this->option('skip-proc')) {
            $setting->getDate()->addSecond();
            $this->info("\nSetting up Stored Procedures migrations.");
            $this->generateProcedures();
        }

        $setting->getDate()->addSecond();
        $this->info("\nSetting up Foreign Key migrations.");
        $this->generateForeignKeys($tables);
    }

    protected function generateSquashedMigrations(Collection $tables, Collection $views): void
    {
        $this->info('Remove old temporary files if any.');
        $this->squash->cleanTemps();

        $this->info('Setting up Tables and Index migrations.');
        $this->generateTablesToTemp($tables);

        if (!$this->option('skip-views')) {
            $this->info("\nSetting up Views migrations.");
            $this->generateViewsToTemp($views);
        }

        if (!$this->option('skip-proc')) {
            $this->info("\nSetting up Stored Procedure migrations.");
            $this->generateProceduresToTemp();
        }

        $this->info("\nSetting up Foreign Key migrations.");
        $this->generateForeignKeysToTemp($tables);

        $migrationFilepath = $this->squash->squashMigrations();

        $this->info("\nAll migrations squashed.");

        if (!$this->shouldLog) {
            return;
        }

        $this->logMigration($migrationFilepath);
    }

    protected function generateTables(Collection $tables): void
    {
        $tables->each(function (string $table): void {
            $path = $this->tableMigration->write(
                $this->schema->getTable($table),
            );

            $this->info("Created: $path");

            if (!$this->shouldLog) {
                return;
            }

            $this->logMigration($path);
        });
    }
}
