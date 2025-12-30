<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CreateTranslationMigration
{
    protected function createMigrationTranslation(string $modelName, $columns)
    {
        $fileName = date('Y_m_d_His') . "_create_{$this->getTranslationTableName($modelName)}_table.php";
        $path = database_path("migrations/{$fileName}");
        if ($this->fileExists($path)) {
            $this->warn("Migration:{$path} already exists");
        }
        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/migration.stub'), $this->getMigrationTranslationStubsVariable($modelName, $this->getTranslationTableColumns($columns)));
        $this->createFile($path, $contents);
        $this->info("Migration: {$path} has been created successfully");
    }

    protected function getTranslationTableName(string $modelName)
    {
        return  Str::plural(Str::snake($modelName));
    }
    protected function getMigrationTranslationStubsVariable(string $modelName, $columns)
    {
        return [
            'TABLE_NAME' => $this->getTranslationTableName($modelName),
            'COLUMNS' => $columns
        ];
    }

    protected function getTranslationTableColumns($columns)
    {
        $columnNames = explode(',', $columns);
        $columns = '';
        $parent_tbl = $this->parent_translation_table_name;
        $parent_model_id = Str::snake($this->parent_model_name)."_id";
        if ($this->translation_model_confirmations) {
            $columns .= "\$table->foreignId('$parent_model_id')->constrained('$parent_tbl');\n\t\t\t";
            $columns .= "\$table->foreignId('language_id')->constrained('languages');\n\t\t\t";
        }

        foreach ($columnNames as $columnName) {
            $columns .= "\$table->string($columnName);\n\t\t\t";
        }
     

        return $columns;
    }
}
