<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CreateMigration
{
    protected function createMigration(string $modelName, $columns)
    {
        $fileName = date('Y_m_d_His') . "_create_{$this->getTableName($modelName)}_table.php";
        $path = database_path("migrations/{$fileName}");
        if ($this->fileExists($path)) {
            $this->warn("Migration:{$path} already exists");
        }
        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/migration.stub'), $this->getMigrationStubsVariable($modelName, $this->getTableColumns($columns)));
        $this->createFile($path, $contents);
        $this->info("Migration: {$path} has been created successfully");
    }

    protected function getTableName(string $modelName)
    {
        $tblName = Str::plural(Str::snake($modelName));
        if ($this->translation_model_confirmations) {
            $this->parent_translation_table_name = $tblName;
            $this->parent_model_name = $modelName;
        }
        return $tblName;
    }
    protected function getMigrationStubsVariable(string $modelName, $columns)
    {
        return [
            'TABLE_NAME' => $this->getTableName($modelName),
            'COLUMNS' => $columns
        ];
    }

    protected function getTableColumns($columns)
    {
        $columnNames = explode(',', $columns);
        $columns = '';


        foreach ($columnNames as $columnName) {
            $columns .= "\$table->string($columnName);\n\t\t\t";
        }


        return $columns;
    }
}
