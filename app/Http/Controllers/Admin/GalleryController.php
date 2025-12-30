<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest as FormRequest;
use App\Models\Gallery as MainModel;
use App\Models\GalleryTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Models\Gallery;
use App\Models\MediaManager;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Services\GeneralService;
use App\Services\MultiLangActivityService;
use  DB, Config, Session, Redirect;

class GalleryController extends BaseController
{

    protected $imageFolderGallery;
    protected $listRouteGallery;
    protected $addRouteGallery;
    protected $editRouteGallery;
    protected $thumbGallery;
    protected $mainTableGallery;
    protected $foreignKeyGallery;
    protected $translationFieldsGallery;
    protected $hasManyRelationGallery;
    protected $mainTableSingularGallery;
    protected $titleGallery;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderGallery = Config::get('constants.GALLERY_FOLDER');
        $this->listRouteGallery = 'admin.gallery.index';
        $this->addRouteGallery = 'admin.gallery.add';
        $this->editRouteGallery = 'admin.gallery.edit';
        $this->thumbGallery  = '/thumb-';
        $this->mainTableGallery  = 'galleries';
        $this->mainTableSingularGallery  = 'gallery';
        $this->foreignKeyGallery  = 'gallery_id';
        $this->translationFieldsGallery  = ['galleries.*', 'gallery_translations.description', 'gallery_translations.title'];
        $this->hasManyRelationGallery = 'gallery_translations';
        $this->titleGallery  = 'Gallery';

        \View::share([
            'imageFolder' => $this->imageFolderGallery,
            'title' =>  $this->titleGallery,
            'listRoute' => $this->listRouteGallery,
            'addRoute' => $this->addRouteGallery,
            'saveRoute' => "admin.gallery.save",
            'updateRoute' => "admin.gallery.update",
            'listUrl' => "gallery-list",
            'formId' => "galleryForm",
            'mainTable' => $this->mainTableGallery,
            'formPath' => 'admin.gallery.form',
            'addPermission' => "GALLERY_MANGER_ADD",
            'editPermission' => "GALLERY_MANGER_EDIT",
            'deletePermission' => "GALLERY_MANGER_DELETE",
            'statusPermission' => "GALLERY_MANGER_CHANGE_STATUS"
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {
        $DB                         =   MainModel::query();
        $fieldsToSearch             =   array('*description-title*' => '*like-like*', 'status' => '=');
        $searchVariable             =    $request->all();
        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableGallery,
                "foreignKey" => $this->foreignKeyGallery,
                "translationFields" => $this->translationFieldsGallery,
                "mainTableSingular" => $this->mainTableSingularGallery
            ]
        );

        extract($output);
        $statusChangeUrl        =    'admin/gallery-list/status/';
        return  View($this->listRouteGallery, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteGallery);
    }

    public function galleryImage(Request $request)
    {
        if ($request->ajax()) {

            $media = MediaManager::paginate(10);
            return view('admin.gallery.media.pagination_data', compact('media'))->render();
        }
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {
        $error = '';
        DB::beginTransaction();

        try {
      
            $row = MainModel::create([
                'status'=>1
            ]);

            if ($request->has('selected_images')) {
                $row->mediaManagers()->attach($request->selected_images);
            }

            $translations = [];
            foreach ($request->description as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'description' => $value,
                    'title' => $request->title[$key],
                ];
            }
            $row->{$this->hasManyRelationGallery}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();

        Session::flash('success', $this->titleGallery . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteGallery);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
      
        $DB   = new MainModel();
        $data = $DB::with($this->hasManyRelationGallery)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        if (!empty($data[$this->hasManyRelationGallery])) {
            foreach ($data[$this->hasManyRelationGallery] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }

        return  View($this->editRouteGallery)->with(['data' => $dataReArrange]);
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
            if ($request->has('selected_images')) {
                $row->mediaManagers()->sync($request->selected_images);
            }

            if ($row->save()) {
                $translations = [];
                foreach ($request->description as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyGallery => $row->id,
                        'language_id' => $key,
                        'description' => $value,
                        'title' => $request->title[$key],
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
        return redirect()->route($this->listRouteGallery)->with('success', $this->titleGallery . __("messages.recordUpdatedSpecific"));
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();

        $row->delete();
        return redirect()->route($this->listRouteGallery)
            ->with('success', $this->titleGallery . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        MainModel::find($id)->update(['status' => $value]);
        return CommonService::redirectStatusChange(redirect()->back()->with('success', $this->titleGallery . __('messages.statusUpdatedSpecific')));
    }
}
