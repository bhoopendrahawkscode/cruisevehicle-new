<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\QuoteRequest as FormRequest;
use App\Models\Quote as MainModel;
use App\Models\QuoteTranslation as TranslationModel;
use App\Services\CommonService;
use App\Services\FilterService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
class QuoteController extends BaseController
{

    protected $listRouteQuote;
    protected $addRouteQuote;
    protected $editRouteQuote;
    protected $viewRouteQuote;
    protected $thumbQuote;
    protected $mainTableQuote;
    protected $foreignKeyQuote;
    protected $translationFieldsQuote;
    protected $hasManyRelationQuote;
    protected $mainTableSingularQuote;
    protected $titleQuote;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteQuote = 'admin.quote.index';
        $this->addRouteQuote = 'admin.quote.add';
        $this->editRouteQuote = 'admin.quote.edit';
        $this->viewRouteQuote = 'admin.quote.view';
        $this->thumbQuote  = '/thumb-';
        $this->mainTableQuote  = 'quotes';
        $this->mainTableSingularQuote  = 'quote';
        $this->foreignKeyQuote  = 'quote_id';
        $this->translationFieldsQuote  = ['quotes.*', 'quote_translations.name','quote_translations.written_by'];
        $this->hasManyRelationQuote = 'quote_translations';
        $this->titleQuote  = __('messages.quote');
        View::share([
            'title' =>  $this->titleQuote,
            'listRoute' => $this->listRouteQuote,
            'addRoute' => $this->addRouteQuote,
            'saveRoute' => "admin.quote.save",
            'updateRoute' => "admin.quote.update",
            'listUrl' => "quote-list",
            'formId' => "quoteForm",
            'mainTable' => $this->mainTableQuote,
            'formPath' => 'admin.quote.form',
            'addPermission' => "QUOTES_MANAGEMENT_ADD",
            'editPermission' => "QUOTES_MANAGEMENT_EDIT",
            'viewPermission' => "QUOTES_MANAGEMENT_VIEW",
            'deletePermission' => "QUOTES_MANAGEMENT_DELETE",
            'statusPermission' => "QUOTES_MANAGEMENT_CHANGE_STATUS",
            'section' =>   __('messages.quotesManagement'),
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $db                         =   MainModel::query();
        $fieldsToSearch             =   array('name' => 'like','day' => '=', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $db,
            'created_at',
            [
                "mainTable" => $this->mainTableQuote,
                "foreignKey" => $this->foreignKeyQuote,
                "translationFields" => $this->translationFieldsQuote,
                "mainTableSingular" => $this->mainTableSingularQuote
            ]
        );
        extract($output);
        $allDays = [];
        for ($i = 1; $i <= 7; $i++) {
            $allDays[$i] = sprintf("%02d", $i);
        }
        $statusChangeUrl        =    'admin/quote-list/status/';
        return  View($this->listRouteQuote, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl','allDays'));
    }

    /**
     * Add Record
     */
    public function add()
    {

        $allDays   =  $this->assignDays();
        return  View($this->addRouteQuote,compact('allDays'));
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
               'day' => $request->day
            ]);

            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'written_by' => $request->written_by[$key],
                ];
            }
            $row->{$this->hasManyRelationQuote}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleQuote . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteQuote);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $db             = new MainModel();
        $data = $db::with($this->hasManyRelationQuote)->where('id', $id)->first();

        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['day'] = sprintf("%02d", $data->day);
        if (!empty($data[$this->hasManyRelationQuote])) {
            foreach ($data[$this->hasManyRelationQuote] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        $allDays[sprintf("%02d", $data->day)] = sprintf("%02d", $data->day);
        return  View($this->editRouteQuote)->with(['data' => $dataReArrange,'allDays'=>$allDays]);
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
            $row->updated_at = date("Y-m-d H:i:s");
            $row->day = $request->day;
            if ($row->save()) {
                $translations = [];
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyQuote => $row->id,
                        'language_id' => $key,
                        'name' => $value,
                        'written_by' => $request->written_by[$key],
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
        Session::flash('success', $this->titleQuote . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteQuote);
    }

    /**
     * View Record
     */

     public function view($id)
     {
         $db             = new MainModel();
         $data = $db::with($this->hasManyRelationQuote)->where('id', $id)->first();

         if (!$data) {
             return abort(403);
         }
         $dataReArrange['recordId'] = $data->id;
         if (!empty($data[$this->hasManyRelationQuote])) {
             foreach ($data[$this->hasManyRelationQuote] as $translationRow) {
                 foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                     $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                 }
             }
         }
         return  View($this->viewRouteQuote)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteQuote)
            ->with('success', $this->titleQuote . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $db = new MainModel();
        $db->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleQuote . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }

    public function assignDays(){
        $allDays = [];
        for ($i = 1; $i <= 7; $i++) {
            $allDays[sprintf("%02d", $i)] = sprintf("%02d", $i);
        }
        $days =  MainModel::orderBy('created_at', 'asc')->pluck('day', 'day')->toArray();
        if(!empty($days)){
            foreach($days as $row){
                if(isset($allDays[sprintf("%02d", $row)])){
                    unset($allDays[sprintf("%02d", $row)]);
                }
            }
        }
        return array_slice($allDays, 0, 1, true);

    }
}
