<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Cms;
use App\Models\CmsTranslation;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\CmsRequest;
use  DB,  Config,  Session, Redirect;
use App\Services\CommonService;
use App\Services\FilterService;

class CMSController extends BaseController
{
    protected $listRoute;
    protected $addRoute;
    protected $editRoute;
    protected $thumb;
    public $mainTable;
    protected $foreignKey;
    protected $translationFields;

    protected $titleCms;

    public function __construct()
    {

        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteCms = 'admin.cms.index';
        $this->addRouteCms = 'admin.cms.add';
        $this->editRouteCms = 'admin.cms.edit';
        $this->thumbCms  = '/thumb-';
        $this->mainTableCms  = 'cmss';
        $this->foreignKeyCms  = 'cms_id';
        $this->translationFieldsCms  = ['cmss.*', 'cms_translations.title'];
        $this->titleCms     =  trans('messages.cms');
        \View::share([
            'title' =>$this->titleCms ,
            'listRoute' => $this->listRouteCms,
            'addRoute' => $this->addRouteCms,
            'saveRoute' => "admin.cms.save",
            'updateRoute' => "admin.cms.update",
            'listUrl' => "cms-list",
            'formId' => "cmsForm",
            'editPermission' => "CMS_MANGER_EDIT",
            'section' =>   __('messages.CMSManager'),
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {


        $DB                         =   Cms::query();
        $fieldsToSearch             =   array('name' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableCms,
                "foreignKey" => $this->foreignKeyCms,
                "translationFields" => $this->translationFieldsCms
            ]
        );
        extract($output);

        return  View($this->listRouteCms)->with([
            'result' => $result, 'searchVariable' => $searchVariable,
            'sortBy' => $sortBy, 'order' => $order, 'query_string' => $query_string, 'mainTable' => $this->mainTableCms
        ]);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB             = new Cms();
        $data = $DB::with('cms_translations')->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['nameMain'] = $data->name;
        if (!empty($data['cms_translations'])) {
            foreach ($data['cms_translations'] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteCms)->with(['data' => $dataReArrange]);
    }

    /**
     * Save Edited Record
     */

    public function update($id, CmsRequest $request)
    {
        $error = "";
        DB::beginTransaction();
        try {

            $data = Cms::findOrFail($id);

            $data['name'] = $request->nameMain ? $request->nameMain : 0;
            $data['updated_at'] = date("Y-m-d H:i:s");
            if ($data->save()) {
                $translations = $this->assignProperties($request, $data);
                CmsTranslation::upsert($translations, ['id']);
            }

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }

        DB::commit();
        Session::flash('success', $this->titleCms.__("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteCms);
    }

    public function assignProperties($request, $data)
    {
        $translations = [];
        foreach ($request->title as $key => $value) {
            $translations[] = [
                'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                'cms_id' => $data->id,
                'language_id' => $key,
                'title' => $value,
                'meta_title' => (isset($request->meta_title[$key])) ? $request->meta_title[$key] : null,
                'meta_keywords' => (isset($request->meta_keywords[$key])) ? $request->meta_keywords[$key] : null,
                'meta_description' => (isset($request->meta_description[$key])) ? $request->meta_description[$key] : null,
                'meta_title' => (isset($request->meta_description[$key])) ? $request->meta_description[$key] : null,
                'body' => (isset($request->body[$key])) ? $request->body[$key] : null,
            ];
        }
        return $translations;
    }
}
