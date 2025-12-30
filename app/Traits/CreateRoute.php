<?php

namespace App\Traits;

trait CreateRoute
{
    protected function createRoute(string $modelName,string $controller)
    {   
      $fileName = $this->getRouteFileName($modelName);
        $path = base_path("routes/{$fileName}");
        if ($this->fileExists($path)) {
            $this->warn("Route:{$path} already exists");
            return;
        }
        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/route.stub'),$this->getRouteStubsVariable($controller,$modelName));
        $this->createFile($path,$contents);
        $this->info("Route: {$path} has been created successfully");
    }

    protected function getRouteStubsVariable(string $controller,string $modelName)
    {

        return [
            'CONTROLLER_NAME' => $controller,
            'MODEL_NAME' => strtolower($modelName),
        ];
    }

    protected function getRouteFileName(string $modelName){
        return $modelName.'_'.date('His').'.php';
    }
}