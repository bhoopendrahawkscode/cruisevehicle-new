<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\BannerRequest as FormRequest;
use App\Models\Banner as MainModel;
use App\Models\BannerTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Models\Banner;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Services\GeneralService;
use App\Services\MultiLangActivityService;
use  DB, Config, Session, Redirect;

class BannerController extends BaseController
{

    protected $imageFolderBanner;
    protected $listRouteBanner;
    protected $addRouteBanner;
    protected $editRouteBanner;
    protected $thumbBanner;
    protected $mainTableBanner;
    protected $foreignKeyBanner;
    protected $translationFieldsBanner;
    protected $hasManyRelationBanner;
    protected $mainTableSingularBanner;
    protected $titleBanner;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderBanner = Config::get('constants.BANNER_FOLDER');
        $this->listRouteBanner = 'admin.banner.index';
        $this->addRouteBanner = 'admin.banner.add';
        $this->editRouteBanner = 'admin.banner.edit';
        $this->thumbBanner  = '/thumb-';
        $this->mainTableBanner  = 'banners';
        $this->mainTableSingularBanner  = 'banner';
        $this->foreignKeyBanner  = 'banner_id';
        $this->translationFieldsBanner  = ['banners.*', 'banner_translations.description', 'banner_translations.title', 'banner_translations.banner_link'];
        $this->hasManyRelationBanner = 'banner_translations';
        $this->titleBanner  = 'Banner';

        \View::share([
            'imageFolder' => $this->imageFolderBanner,
            'title' =>  $this->titleBanner,
            'listRoute' => $this->listRouteBanner,
            'addRoute' => $this->addRouteBanner,
            'saveRoute' => "admin.banner.save",
            'updateRoute' => "admin.banner.update",
            'listUrl' => "banner-list",
            'formId' => "bannerForm",
            'mainTable' => $this->mainTableBanner,
            'formPath' => 'admin.banner.form',
            'addPermission' => "BANNER_MANGER_ADD",
            'editPermission' => "BANNER_MANGER_EDIT",
            'deletePermission' => "BANNER_MANGER_DELETE",
            'statusPermission' => "BANNER_MANGER_CHANGE_STATUS"
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {
        $DB                         =   MainModel::query();
        $fieldsToSearch             =   array('title' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();
        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableBanner,
                "foreignKey" => $this->foreignKeyBanner,
                "translationFields" => $this->translationFieldsBanner,
                "mainTableSingular" => $this->mainTableSingularBanner
            ]
        );

        extract($output);
        $statusChangeUrl        =    'admin/banner-list/status/';
        return  View($this->listRouteBanner, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteBanner);
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {
        $error = '';

        DB::beginTransaction();
        try {
            $fileName = '';
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderBanner);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderBanner,
                    150,
                    100,
                    $fileName
                );
            }
            $row = MainModel::create([
                'image'        => $fileName
            ]);

            $translations = [];
            foreach ($request->description as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'description' => $value,
                    'title' => $request->title[$key],
                    'banner_link' => $request->banner_link[$key],
                ];
            }
            $row->{$this->hasManyRelationBanner}()->createMany($translations);

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();

        Session::flash('success', $this->titleBanner . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteBanner);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB   = new MainModel();
        $data = $DB::with($this->hasManyRelationBanner)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data[$this->hasManyRelationBanner])) {
            foreach ($data[$this->hasManyRelationBanner] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteBanner)->with(['data' => $dataReArrange]);
    }

    /**
     * Save Edited Record
     */

    public function update($id, FormRequest $request)
    {
        $error = "";
        DB::beginTransaction();

        try {
            $row = MainModel::findOrFail($id);
            if (!empty($request->image)) {
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderBanner);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderBanner,
                    380,
                    100,
                    $fileName
                );
                $row->image = $fileName;
            }
            $row->updated_at = date("Y-m-d H:i:s");

            if ($row->save()) {
                $translations = [];
                foreach ($request->description as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyBanner => $row->id,
                        'language_id' => $key,
                        'description' => $value,
                        'title' => $request->title[$key],
                        'banner_link' => $request->banner_link[$key],
                    ];
                }
                TranslationModel::upsert($translations, ['id']);
            }
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }

        if ($error !== "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }

        DB::commit();
        Session::flash('success', $this->titleBanner . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteBanner);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $banner = new Banner();
        $attributes = $banner->getTranslationAttributes();
        $languages_ids = $row->banner_translations()->pluck('language_id');
        $translations = $row->banner_translation()->whereIn('language_id', $languages_ids)->get()->keyBy('language_id');

        $row->delete();
        return redirect()->route($this->listRouteBanner)
            ->with('success', $this->titleBanner . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $DB = new MainModel();
        $DB->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        Session::flash('success', $this->titleBanner . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());
    }
}
