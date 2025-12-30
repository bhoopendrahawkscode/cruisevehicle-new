<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\Request;

class MultiLangActivityService
{
    public static function UpdateLogActivity(object $model, object $hasManyTranslations): void
    {
        $request = Request::all();
        $attributes = $model->getTranslationAttributes();
        if(self::CheckExistKeys($request,$attributes)){
            $properties = [];
            $languages_ids = self::getLangIds($request, $attributes);
            $translations = self::getTranslations($model, $hasManyTranslations);
            foreach ($languages_ids as $language_id) {
                $lang_name = self::getLangName($language_id);
                $translation = $translations->get($language_id);
                foreach ($attributes as $attr) {
                    $properties[$lang_name]['old'][$attr] = $translation->$attr;
                    $properties[$lang_name]['new'][$attr] = $request[$attr][$language_id];
                }
            }
            GeneralService::UpdateStatusActivityLog($model, 'updated', $properties, true);
        }
      
    }
    public static function CreateLogActivity(object $model): void
    {
        $request = Request::all();
        $properties = [];
        $attributes = $model->getTranslationAttributes();

        $languages_ids = self::getLangIds($request, $attributes);
      
        foreach ($languages_ids as $language_id) {
            $lang_name = self::getLangName($language_id);
            foreach ($attributes as $attr) {
                $properties[$lang_name]['new'][$attr] = $request[$attr][$language_id];
            }
        }
        GeneralService::UpdateStatusActivityLog($model, 'created', $properties, true);
    }

    public static function DeleteActivity(object $model, object $hasManyTranslations, object $hasOneTranslation): void
    {
        $properties = [];
        $languages_ids = $hasManyTranslations->pluck('language_id');
        $translations = $hasOneTranslation->whereIn('language_id', $languages_ids)->get()->keyBy('language_id');
        $attributes = $model->getTranslationAttributes();


        foreach ($languages_ids as $language_id) {
            $lang_name = self::getLangName($language_id);
            $translation = $translations->get($language_id);
            foreach ($attributes as $attr) {
                $properties[$lang_name]['old'][$attr] = $translation->$attr;
            }
        }
        GeneralService::UpdateStatusActivityLog($model, 'deleted', $properties, true);
    }




    protected static function getLangName($lang_id)
    {
        $language = Language::find($lang_id);
        return $language->name;
    }

    protected static function getLangIds($request, $attributes)
    {
        return collect($request[$attributes[0]])->keys();
    }

    protected static function getTranslations(object $model, object $hasManyTranslations)
    {

        $languages_ids = self::getLangIds(Request::all(), $model->getTranslationAttributes());
        return $hasManyTranslations->whereIn('language_id', $languages_ids)->get()->keyBy('language_id');
    }

    public static function CreateStatusLog(object $model): void
    {
        $request = Request::all();
        $attributes = $model->getTranslationAttributes();
        if(!self::CheckExistKeys($request,$attributes)){
            $old_status = $model->getOriginal('status');
            $new_status = $model->status;
            $properties = [
                "old" => [
                    "status" => $old_status,
                ],
                "attributes" => [
                    "status" => $new_status
                ]
            ];
            GeneralService::UpdateStatusActivityLog($model, 'updated', $properties, false);
        }
    

    }

    public static function CheckExistKeys(array $request, array $attributes){
        foreach ($attributes as $key) {
            if (array_key_exists($key, $request)) {
                return true;
            }
        }
        return false;
    }
}
