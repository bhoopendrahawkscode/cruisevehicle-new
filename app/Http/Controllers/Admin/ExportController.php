<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Exports\ExportableDataExport;
use App\Services\CommonService;
use Illuminate\Support\Collection;
use App\Constants\Constant;
use Excel;
use Illuminate\Support\Facades\Config;

class ExportController extends BaseController
{
    protected $modelName = '';
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
    }

    public function export(Request $request, $modelName, $format)
    {
        $this->modelName = $modelName;
        $modelClass = 'App\Models\\' . $modelName;
        $translationModel = $modelName . 'Translation';
        $modelTranslationClass = 'App\Models\\' . $translationModel;
        $mainModelStatus = 1;
        $translationModelStatus = 1;
        $excludeModelFields = CommonService::exportExcludeFields($modelName);

        if (!class_exists($modelClass)) {
            $mainModelStatus = 0;
            $error = ['error' => 'Model not found'];
        }
        if (!class_exists($modelTranslationClass)) {
            $translationModelStatus = 0;
            // Handle model not found
            $error = ['error' => 'Model Translation not found'];
        }

        if ($mainModelStatus == 1) {
            $data = $this->getMainModelData($modelClass);
        } else {
            return response()->json($error, 404);
        }

        if ($mainModelStatus == 1 && $translationModelStatus == 1) {
            $relationshipModel = strtolower($this->modelName) . '_translation';
            $relationshipModelId = strtolower($this->modelName) . '_id';
            $data = $this->getRelationshipModelData($modelClass, $relationshipModel);
            $excludeFields = array();
            $excludeFields = array($relationshipModel, $relationshipModelId, 'language_id');
            $excludeModelFields = array_merge($excludeModelFields, $excludeFields);
        }

        if ($format === 'xls') {
            $result =  Excel::download(new ExportableDataExport($data, $excludeModelFields), $modelName . '.xlsx');
        } elseif ($format === 'csv') {
            $result = Excel::download(new ExportableDataExport($data, $excludeModelFields), $modelName . '.csv');
        } else {
            // Handle invalid format
            $result = response()->json(['error' => 'Invalid format'], 400);
        }
        return $result;
    }

    public function getMainModelData($modelClass)
    {

        $mainModel = array();
        $data = array();
        $mainModel = $modelClass::all();
        if (!empty($mainModel)) {
            foreach ($mainModel->toArray() as $index => $modelData) {
                $modelData['media_url'] =  $this->getImageURl($modelData);
                $data[$index] = $modelData;
            }
            $data = collect($data);
        }
        return $data;
    }

    public function getRelationshipModelData($modelClass, $relationshipModel)
    {
        $translationModelData = $modelClass::with([$relationshipModel => function ($query) {
            $languageId = CommonService::getLangIdFromLocale();
            $query->where('language_id', $languageId);
        }])->get();

        return $this->getData($translationModelData, $relationshipModel);
    }

    public function getData($translationModelData, $relationshipModel)
    {
        $data = array();
        if (!empty($translationModelData)) {
            foreach ($translationModelData->toArray() as $index => $modelData) {
                $modelData = $this->processModelData($modelData);
                $modelData['media_url'] = $this->getImageURl($modelData);
                $data[$index] = array_merge($modelData, $modelData[$relationshipModel]);
            }
            return collect($data);
        }
    }

    private function processModelData($modelData)
    {
        if ($this->modelName == 'Audio') {
            $modelData['audio_url'] = $this->getMediaUrl($modelData, 'audio');
        } elseif ($this->modelName == 'Video') {
            $modelData['video_url'] = $this->getMediaUrl($modelData, 'video');
        }
        return $modelData;
    }

    public function getMediaUrl($modelData, $mediaType)
    {
        return ($modelData[$mediaType] != '') ? Constant::S3_URL . $modelData[$mediaType] : '';
    }

    public function getImageURl($modelData = array())
    {
        $mediaUrl = '';
        $folders = [
            'User' => 'constants.USER_FOLDER',
            'Portfolio' => 'constants.PORTFOLIO_FOLDER',
            'Audio' => 'constants.AUDIO_FOLDER',
            'Gallery' => 'constants.GALLERY_FOLDER',
            'Testimonial' => 'constants.TESTIMONIALS_FOLDER',
            'Banner' => 'constants.BANNER_FOLDER',
        ];
    
        if (!empty($modelData['image']) && isset($folders[$this->modelName])) {
            $mediaUrl = Config::get($folders[$this->modelName]) . $modelData['image'];
        }
    
        return !empty($mediaUrl) ? ImageService::getImageUrl($mediaUrl) : '';
    }
    
    
}
