<?php

namespace App\Traits;
trait CreateSimpleModel
{


    protected function createSimpleModel(string $modelName,$columns)
    {
        $path = app_path("Models/{$modelName}.php");
        if ($this->fileExists($path)) {
            $this->warn("Model:{$path} already exists");
            return;
        }
        
        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/simple-model.stub'),$this->getModelStubsVariable($modelName,$columns));
        $this->createFile($path,$contents);
        $this->info("Model: {$path} has been created successfully");
      
    }


    protected function getModelStubsVariable(string $modelName,$columns){
       
        return [
            'MODEL_NAME'=>$modelName,
            'PROPERTIES'=>$columns
        ];
    }

}
