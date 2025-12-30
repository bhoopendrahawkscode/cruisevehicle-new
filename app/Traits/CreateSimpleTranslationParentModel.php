<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait CreateSimpleTranslationParentModel
{


    protected function createSimpleTranslationParentModel(string $modelName, $columns, string $translation_model_name)
    {
        $path = app_path("Models/{$modelName}.php");
        if ($this->fileExists($path)) {
            $this->warn("Model:{$path} already exists");
            return;
        }

        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/simple-translation-parent-model.stub'), $this->getSimpleTranslationModelStubsVariable($modelName, $columns, $translation_model_name));
        $this->createFile($path, $contents);
        $this->info("Model: {$path} has been created successfully");
    }



    protected function getSimpleTranslationModelStubsVariable(string $modelName, $columns, $translation_model_name)
    {
        return [
            'MODEL_NAME' => $modelName,
            'PROPERTIES' => $columns,
            'SMALL_MODEL_NAME' =>Str::snake($modelName),
            'TRANSLATION_MODEL_NAME' => $translation_model_name
        ];
    }
}
