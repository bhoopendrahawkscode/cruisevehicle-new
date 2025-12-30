<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\MetaRequest;
use App\Models\Meta;
use App\Models\MetaTranslation;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class MetaManagementController extends BaseController
{

    protected $mainTable;
    protected $foreignKey;
    protected $translationFields;
    protected $listRoute;
    protected $imageFolder;
    protected $title;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->mainTable = 'metas';
        $this->foreignKey = 'meta_id';
        $this->listRoute = 'meta.tag.list';
        $this->imageFolder = Config::get('constants.META_FOLDER');
        $this->title = trans('messages.meta');

        View::share([
            'editPermission' => 'CMS_MANGER_EDIT',
            'listRoute' => $this->listRoute,
            'title' => $this->title,
        ]);
    }

    public function list(Request $request)
    {
        $MetaTranslation = new MetaTranslation();
        $DB                         =   Meta::query();
        $fieldsToSearch             =   array('name' => 'like', '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTable,
                "foreignKey" => $this->foreignKey,
                "translationFields" => $MetaTranslation->getAttributes(),
            ]
        );
        extract($output);
        return  view('admin.meta.index')->with([
            'result' => $result, 'searchVariable' => $searchVariable,
            'sortBy' => $sortBy, 'order' => $order, 'query_string' => $query_string, 'mainTable' => $this->mainTable
        ]);
    }

    public function  add()
    {
        return view('admin.meta.add');
    }
    public function edit(Meta $meta)
    {

        $data = $meta->where('id',$meta->id)->with('meta_translations')->first();
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data['meta_translations'])) {
            foreach ($data['meta_translations'] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
       
        $data= $dataReArrange;
        return view('admin.meta.edit', compact('meta', 'data'));
    }

    public function validateName(Request $request){
        if ($request->name) {
            $query = Meta::where('name', $request->name);
            if ($request->id) {
                $query->where('id', '!=', $request->id);
            }
            $user = $query->first();
            if ($user) {
                return "false";
            } else {
                return "true";
            }
        }
        return "true";
    }

    public function save(MetaRequest $request)
    {
        try {
            DB::beginTransaction();
            $translations = [];
            $fileName = '';
            foreach ($request->meta_title as $key => $value) {
                if ($request->image && isset($request->image[$key])) {
                    $fileName  = ImageService::fileUploadImage($request->image[$key], '', $this->imageFolder);
                    ImageService::manipulateImage(
                        Constant::OPERATION_TYPE,
                        $request->image[$key],
                        $this->imageFolder,
                        150,
                        100,
                        $fileName
                    );

                    $translations[] = [
                        'language_id' => $key,
                        'meta_title' => $request->meta_title[$key],
                        'meta_key' => $request->meta_key[$key],
                        'meta_description' => $request->meta_description[$key],
                        'image' => $fileName
                    ];
                } else {
                    $translations[] = [
                        'language_id' => $key,
                        'meta_title' => $request->meta_title[$key],
                        'meta_key' => $request->meta_key[$key],
                        'meta_description' => $request->meta_description[$key],

                    ];
                }
            }

            $input = $request->only('name', 'url');
            $meta = Meta::create($input);
            $meta->meta_translations()->createMany($translations);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->title . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }


    public function update(MetaRequest $request, Meta $meta)
    {
        try {
            DB::beginTransaction();
            $translationsData = [];
            $file = '';
            foreach ($request->meta_title as $key => $value) {
                if ($request->image && isset($request->image[$key]) && $request->hasFile('image')) {
                    $file  = ImageService::fileUploadImage($request->image[$key], '', $this->imageFolder);
                    ImageService::manipulateImage(
                        Constant::OPERATION_TYPE,
                        $request->image[$key],
                        $this->imageFolder,
                        150,
                        100,
                        $file
                    );

                    $translationsData[] = [
                        'id' => $request->meta_translations_id[$key],
                        'language_id' => $key,
                        'meta_title' => $request->meta_title[$key],
                        'meta_key' => $request->meta_key[$key],
                        'meta_description' => $request->meta_description[$key],
                        'image' => $file
                    ];
                } else {
                    $translationsData[] = [
                        'id' => $request->meta_translations_id[$key],
                        'language_id' => $key,
                        'meta_title' => $request->meta_title[$key],
                        'meta_key' => $request->meta_key[$key],
                        'meta_description' => $request->meta_description[$key],
                    ];
                }
            }

            $input_data = $request->only('name', 'url');
            $meta->update($input_data);
            $meta->updateManyTranslations($translationsData, $meta);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->title . __("messages.recordUpdatedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
}
