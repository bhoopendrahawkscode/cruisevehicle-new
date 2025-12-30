<?php

namespace App\Traits;

use BaconQrCode\Common\Mode;

trait CrateCrudBlade
{
    private function GenerateCrudBladeFile(string $fileName, string $stub_path, array $getStubVariable = [])
    {
        $path = resource_path("views/admin/{$fileName}");
        if ($this->fileExists($path)) {
            $this->warn("Blade:{$path} already exists");
            return;
        }
        $this->makeDir(dirname($path));
        $contents = $this->getStubContents(base_path($stub_path), $getStubVariable);
        $this->createFile($path, $contents);
        $this->info("Blade: {$path} has been created successfully");
    }

    protected function createListBlade(string $modelName)
    {
        $fileName = $this->getListBladeName($modelName);
        $this->GenerateCrudBladeFile($fileName, 'stubs/blade-list.stub', $this->bladeListStubsVariable($modelName));
    }


    protected function createFormBlade(string $modelName)
    {
        $fileName = $this->getFormBladeName($modelName);
        if ($this->translation_model_confirmations) {
            $translations_fields = '';
            $none_translations_fields='';
            $translation_columns = explode(',', $this->child_translation_model_table_columns);
            $none_translations_columns = explode(',', $this->parent_translation_model_table_columns);
            foreach ($translation_columns as $columnName) {
                $translations_fields .=  $this->getStubContents(base_path('stubs/others/fields_stubs/blade-translations-fields.stub'), [
                    'TRANSLATION_FIELD_NAME' => str_replace("'", "", $columnName)
                ]);
            }

            foreach ($none_translations_columns as $columnName) {
                $none_translations_fields .=  $this->getStubContents(base_path('stubs/others/fields_stubs/input-field.stub'), [
                    'FIELD_NAME' => str_replace("'", "", $columnName)
                ]);
            }

            $this->GenerateCrudBladeFile($fileName, 'stubs/others/blade-translation-tabs-form.stub', [
                'TRANSLATIONS_FIELDS' => $translations_fields,
                'NONE_TRANSLATION_FIELDS' => $none_translations_fields,
                'SMALL_MODEL_NAME'=>$this->getSmallModelName($modelName),
            ]);
            
        } else {
            $this->GenerateCrudBladeFile($fileName, 'stubs/blade-form.stub', $this->bladeFormStubsVariable($modelName));
        }
    }
    protected function createEditBlade(string $modelName)
    {
        $fileName = $this->getEditBladeName($modelName);

        if ($this->translation_model_confirmations) {
            $this->GenerateCrudBladeFile($fileName, 'stubs/others/fields_stubs/blade-translations-fields.stub', $this->bladeEditStubsVariable($modelName));
        }else{
            $this->GenerateCrudBladeFile($fileName, 'stubs/blade-edit.stub', $this->bladeEditStubsVariable($modelName));
        }
    
    }

    protected function createAddBlade(string $modelName)
    {
        $fileName = $this->getAddBladeName($modelName);
        $this->GenerateCrudBladeFile($fileName, 'stubs/blade-add.stub', $this->bladeAddStubsVariable($modelName));
    }

    
    protected function bladeListStubsVariable(string $modelName)
    {
        return [
            'SMALL_MODEL_NAME' => $this->getSmallModelName($modelName)
        ];
    }

    protected function bladeFormStubsVariable(string $modelName)
    {
        return [
            'SMALL_MODEL_NAME' => $this->getSmallModelName($modelName)
        ];
    }
    protected function  bladeEditStubsVariable(string $modelName)
    {
        return [
            'SMALL_MODEL_NAME' => $this->getSmallModelName($modelName)
        ];
    }

    protected function bladeAddStubsVariable(string $modelName)
    {
        return [
            'SMALL_MODEL_NAME' => $this->getSmallModelName($modelName)
        ];
    }

    protected function getListBladeName(string $modelName)
    {
        return $this->getSmallModelName($modelName) . '/index.blade.php';
    }

    protected function getFormBladeName(string $modelName)
    {
        return $this->getSmallModelName($modelName) . '/form.blade.php';
    }

    protected function getEditBladeName(string $modelName)
    {
        return $this->getSmallModelName($modelName) . '/edit.blade.php';
    }

    protected function getAddBladeName(string $modelName)
    {
        return $this->getSmallModelName($modelName) . '/add.blade.php';
    }
}
