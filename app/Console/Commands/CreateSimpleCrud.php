<?php

namespace App\Console\Commands;

use App\Traits\CreateController;
use App\Traits\CreateMigration;
use App\Traits\CreateSimpleModel;
use App\Traits\CreateSimpleTranslationChildModel;
use App\Traits\CreateSimpleTranslationParentModel;
use App\Traits\CreateTranslationMigration;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use function Laravel\Prompts\{text, textarea, confirm};

class CreateSimpleCrud extends Command
{
    protected $translation_model_confirmations;
    protected $parent_translation_table_name;
    protected $parent_model_name;
    protected $parent_translation_model_table_columns;
    protected $child_translation_model_table_columns;
    use CreateSimpleModel, CreateMigration, CreateController,CreateSimpleTranslationParentModel,CreateSimpleTranslationChildModel,CreateTranslationMigration;
    protected $signature = 'create:simple-crud';


    protected $description = 'Command description';

    protected $files;
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }
    protected function fileExists(string $path)
    {
        if ($this->files->exists($path)) {
            return true;
        }
        return false;
    }
    protected function getSmallModelName(string $modelName){
        return Str::snake($modelName);
    }

    public function createFile(string $path, $contents)
    {
        $this->files->put($path, $contents);
        return $path;
    }

    public function  getStubContents(string $stub, array $stubVar = [])
    {
        $contents = file_get_contents($stub);
        if (!empty($stubVar)) {
            foreach ($stubVar as $search => $replace) {
                $contents = str_replace('${{' . $search . '}}$', $replace, $contents);
            }
        }

        return $contents;
    }


    protected function makeDir(string $path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        return $path;
    }

    protected function crudGenerator()
    {
        $modelName = $this->getModelName();
        $columns = $this->getColumnNames();
        $this->parent_translation_model_table_columns = $columns;
        
        if (!$this->translation_model_confirmations) {
            $this->createSimpleModel($modelName, $columns);
            $this->createMigration($modelName, $columns);
            $this->createController($modelName);
        }else{
            $translation_model_name = $modelName.'Translation';
            $translation_column_names = $this->getTranslationsColumnNames();
            $this->child_translation_model_table_columns = $translation_column_names;
            $this->createSimpleTranslationParentModel($modelName,$columns,$translation_model_name);
            $this->CreateSimpleTranslationChildModel($translation_model_name,$translation_column_names);
            $this->createMigration($modelName,$columns);
            $this->createMigrationTranslation($translation_model_name,$translation_column_names);
            $this->createController($modelName);

            
        }
    }


    protected function getColumnNames()
    {
        return textarea(
            label: 'Enter your columns',
            placeholder: "'first_name','last_name','email'",
        );
        
    }
    protected function getTranslationsColumnNames()
    {
        return textarea(
            label: 'Please enter columns for translation table',
            placeholder: "'first_name','last_name','email'",
        );
    }
    

    protected function getModelName()
    {
        $modelName = text(
            label: 'Enter your model name?',
            placeholder: 'e.g User',
            validate: ['required', 'string'],
        );
        return ucfirst($modelName);
    }

    public function handle()
    {
        $this->translation_model_confirmations = confirm(
            label: 'Do you want to work with translation tables?',
            default: false,
            yes: 'Yes',
            no: 'No',
        );

        $this->crudGenerator();
    }
}
