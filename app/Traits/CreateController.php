<?php

namespace App\Traits;


trait CreateController
{
    use CrateCrudBlade, CreateRoute, CreateSimpleRequest;
    protected function createController(string $modelName)
    {
        $controllerName = $this->getControllerName($modelName);
        $path = app_path("Http/Controllers/Admin/{$controllerName}.php");
        if ($this->fileExists($path)) {
            $this->warn("Controller:{$path} already exists");
            return;
        }

        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path('stubs/controller-crud.stub'), $this->getControllerStubsVariable($modelName));
        $this->createFile($path, $contents);
        $this->info("Controller: {$path} has been created successfully");
        $this->generateCrudBlade($modelName);
        $this->generateRoute($modelName, $controllerName);
        $this->createSimpleRequest($modelName);
    }

    protected function getControllerName(string $modelName)
    {
        return  ucfirst($modelName) . 'Controller';
    }

    protected function getControllerStubsVariable(string $modelName)
    {
        $other_for_save = '';
        $other_for_update='';
        $controller_edit_method ='';
        $small_model_name = $this->getSmallModelName($modelName);
        $use_create_variable = "$" . $small_model_name;
        $columnNames = explode(',', $this->child_translation_model_table_columns);
        $request = '$request';
        $bracket_key = '[$key]';

        if ($this->translation_model_confirmations) {
            $translation_columns = '';
            foreach ($columnNames as $columnName) {
                $translation_columns .= "$columnName=>$request" . "[" . $columnName . "]" . $bracket_key . ",\n\t\t\t";
            }

            $other_for_save = $this->getStubContents(base_path('stubs/others/save-method-translation-controller.stub'), [
                'FIRST_TRANSLATION_COLUMN_NAME' => $columnNames[0],
                'TRANSLATION_COLUMNS' => $translation_columns,
                'USE_VARIABLE_NAME' => $use_create_variable,
                'SMALL_MODEL_NAME' => $small_model_name,
            ]);
            $other_for_update = $this->getStubContents(base_path('stubs/others/update-method-translation-controller.stub'), [
                'FIRST_TRANSLATION_COLUMN_NAME' => $columnNames[0],
                'TRANSLATION_COLUMNS' => $translation_columns,
                'USE_VARIABLE_NAME' => $use_create_variable,
                'SMALL_MODEL_NAME' => $small_model_name,
            ]);
            $controller_edit_method = $this->getStubContents(base_path('stubs/others/controllers/methods/translation-edit-method.stub'),[
                'MODEL'=>$modelName,
                'SMALL_MODEL_NAME'=>$small_model_name,
            ]);
           
        }else{
            $controller_edit_method = $this->getStubContents(base_path('stubs/others/controllers/methods/simple-edit-method.stub'),[
                'MODEL'=>$modelName,
                'SMALL_MODEL_NAME'=>$small_model_name,
            ]);
          
        }


        return [
            'MODEL' => $modelName,
            'CONTROLLER_NAME' => $this->getControllerName($modelName),
            'LIST_VIEW_BLADE_PATH' => "admin." . strtolower($modelName) . ".index",
            'SMALL_MODEL_NAME' => $small_model_name,
            'REQUEST_NAME' => $this->getSimpleRequestFileName($modelName),
            'USE_CREATE_VARIABLE' => ($this->translation_model_confirmations) ? $use_create_variable . '=' : '',
            'OTHER_FOR_SAVE' => ($this->translation_model_confirmations) ? $other_for_save : '',
            'OTHER_FOR_UPDATE' => ($this->translation_model_confirmations) ? $other_for_update : '',
            'CONTROLLER_EDIT_METHOD'=>$controller_edit_method

        ];
    }

    protected function generateCrudBlade(string $modelName)
    {
        $this->createListBlade($modelName);
        $this->createFormBlade($modelName);
        $this->createAddBlade($modelName);
        $this->createEditBlade($modelName);
    }

    protected function generateRoute(string $modelName, string $controller)
    {
        $this->createRoute($modelName, $controller);
    }
}
