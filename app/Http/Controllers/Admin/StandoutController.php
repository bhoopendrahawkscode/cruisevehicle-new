<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\StandoutRequest as FormRequest;
use App\Models\Standout as MainModel;
use App\Models\StandoutTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
class StandoutController extends BaseController
{
    protected $imageFolderStandout;
    protected $listRouteStandout;
    protected $addRouteStandout;
    protected $editRouteStandout;
    protected $viewRouteStandout;
    protected $thumbStandout;
    protected $mainTableStandout;
    protected $foreignKeyStandout;
    protected $translationFieldsStandout;
    protected $hasManyRelationStandout;
    protected $mainTableSingularStandout;
    protected $titleStandout;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderStandout = Config::get('constants.STANDOUT_FOLDER');
        $this->listRouteStandout = 'admin.standout.index';
        $this->addRouteStandout = 'admin.standout.add';
        $this->editRouteStandout = 'admin.standout.edit';
        $this->viewRouteStandout = 'admin.standout.view';
        $this->thumbStandout  = '/thumb-';
        $this->mainTableStandout  = 'standouts';
        $this->mainTableSingularStandout  = 'standout';
        $this->foreignKeyStandout  = 'standout_id';
        $this->translationFieldsStandout  = ['standouts.*', 'standout_translations.name'];
        $this->hasManyRelationStandout = 'standout_translations';
        $this->titleStandout  = __('messages.goodNews');
        View::share([
            'imageFolder' => $this->imageFolderStandout,
            'title' =>  $this->titleStandout,
            'listRoute' => $this->listRouteStandout,
            'addRoute' => $this->addRouteStandout,
            'saveRoute' => "admin.standout.save",
            'updateRoute' => "admin.standout.update",
            'listUrl' => "good-news-list",
            'formId' => "standoutForm",
            'mainTable' => $this->mainTableStandout,
            'formPath' => 'admin.standout.form',
            'addPermission' => "GOOD_NEWS_MANAGEMENT_ADD",
            'editPermission' => "GOOD_NEWS_MANAGEMENT_EDIT",
            'viewPermission' => "GOOD_NEWS_MANAGEMENT_VIEW",
            'deletePermission' => "GOOD_NEWS_MANAGEMENT_DELETE",
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
            'section' =>   __('messages.goodNewsManagement'),
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $db                         =   MainModel::query();
        $fieldsToSearch             =   array('name' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $db,
            'created_at',
            [
                "mainTable" => $this->mainTableStandout,
                "foreignKey" => $this->foreignKeyStandout,
                "translationFields" => $this->translationFieldsStandout,
                "mainTableSingular" => $this->mainTableSingularStandout
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/good-news-list/status/';
        return  View($this->listRouteStandout, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteStandout);
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
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderStandout);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderStandout,
                    150,
                    100,
                    $fileName
                );
            }
            $row = MainModel::create([
                'image'        => $fileName
            ]);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'description' => $request->description[$key],
                ];
            }
            $row->{$this->hasManyRelationStandout}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleStandout . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteStandout);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $dataReArrange      =       $this->getEditView($id);
        return  View($this->editRouteStandout)->with(['data' => $dataReArrange]);
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
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderStandout);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderStandout,
                    200,
                    200,
                    $fileName
                );
                $row->image = $fileName;
            }
            $row->updated_at = date("Y-m-d H:i:s");
            if ($row->save()) {
                $translations = [];
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyStandout => $row->id,
                        'language_id' => $key,
                        'name' => $value,
                        'description' => $request->description[$key],
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
        Session::flash('success', $this->titleStandout . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteStandout);
    }

    /**
     * View Record
     */

     public function view($id)
     {
        $dataReArrange      =       $this->getEditView($id);
        return  View($this->viewRouteStandout)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteStandout)
            ->with('success', $this->titleStandout . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $db = new MainModel();
        $db->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleStandout . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }
    public function getEditView($id){
        $db                     = new MainModel();
        $dataStandout           = $db::with($this->hasManyRelationStandout)->where('id', $id)->first();

        if (!$dataStandout) {
            return abort(403);
        }
        $dataReArrangeStandout['recordId'] = $dataStandout->id;
        $dataReArrangeStandout['thumbImage'] = $dataStandout->thumbImage;
        if (!empty($dataStandout[$this->hasManyRelationStandout])) {
            foreach ($dataStandout[$this->hasManyRelationStandout] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrangeStandout[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return $dataReArrangeStandout;
    }
}
