<?php

namespace App\Traits;

trait CreateSimpleRequest
{
    protected function createSimpleRequest(string $modelName)
    {
        $fileName = $this->getSimpleRequestFileName($modelName);
        $path = app_path("Http/Requests/{$fileName}.php");
        if ($this->fileExists($path)) {
            $this->warn("Request:{$path} already exists");
            return;
        }

        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/simple-request.stub'), $this->getSimpleRequestStubVariable($modelName));
        $this->createFile($path, $contents);
        $this->info("Request: {$path} has been created successfully");
    }

    protected function getSimpleRequestStubVariable(string $modelName){
        return [
            'SMALL_MODEL_NAME'=>strtolower($modelName),
            'TABLE_NAME'=>$this->getTableName($modelName)
        ];
    }

    protected function getSimpleRequestFileName(string $modelName){
        return $modelName.'Request';
    }
}
