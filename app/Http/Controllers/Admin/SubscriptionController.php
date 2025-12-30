<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\SubscriptionRequest as FormRequest;
use App\Models\Subscription as MainModel;
use App\Models\SubscriptionTranslation as TranslationModel;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
class SubscriptionController extends BaseController
{

    protected $listRouteSubscription;
    protected $addRouteSubscription;
    protected $editRouteSubscription;
    protected $viewRouteSubscription;
    protected $thumbSubscription;
    protected $mainTableSubscription;
    protected $foreignKeySubscription;
    protected $translationFieldsSubscription;
    protected $hasManyRelationSubscription;
    protected $mainTableSingularSubscription;
    protected $titleSubscription;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteSubscription = 'admin.subscription.index';
        $this->addRouteSubscription = 'admin.subscription.add';
        $this->editRouteSubscription = 'admin.subscription.edit';
        $this->viewRouteSubscription = 'admin.subscription.view';
        $this->thumbSubscription  = '/thumb-';
        $this->mainTableSubscription  = 'subscriptions';
        $this->mainTableSingularSubscription  = 'subscription';
        $this->foreignKeySubscription  = 'subscription_id';
        $this->translationFieldsSubscription  = ['subscriptions.*', 'subscription_translations.name','subscription_translations.price','subscription_translations.validity'];
        $this->hasManyRelationSubscription = 'subscription_translations';
        $this->titleSubscription  = __('messages.subscription');
        View::share([
            'title' =>  $this->titleSubscription,
            'listRoute' => $this->listRouteSubscription,
            'addRoute' => $this->addRouteSubscription,
            'saveRoute' => "admin.subscription.save",
            'updateRoute' => "admin.subscription.update",
            'listUrl' => "subscription-list",
            'formId' => "subscriptionForm",
            'mainTable' => $this->mainTableSubscription,
            'formPath' => 'admin.subscription.form',
            'addPermission' => "SUBSCRIPTION_MANAGEMENT_ADD",
            'editPermission' => "SUBSCRIPTION_MANAGEMENT_EDIT",
            'viewPermission' => "SUBSCRIPTION_MANAGEMENT_VIEW",
            'deletePermission' => "SUBSCRIPTION_MANAGEMENT_DELETE",
            'statusPermission' => "SUBSCRIPTION_MANAGEMENT_CHANGE_STATUS",
            'section' =>   __('messages.subscriptionManagement'),
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
                "mainTable" => $this->mainTableSubscription,
                "foreignKey" => $this->foreignKeySubscription,
                "translationFields" => $this->translationFieldsSubscription,
                "mainTableSingular" => $this->mainTableSingularSubscription
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/subscription-list/status/';
        return  View($this->listRouteSubscription, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteSubscription);
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
            ]);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'validity' => $request->validity[$key],
                    'songs_service' => $request->songs_service[$key],
                    'video_service' => $request->video_service[$key],
                    'subscription_type' =>$request->subscription_type[$key],
                    'price' =>isset($request->price[$key]) ? $request->price[$key] : null,
                    'video_price' => isset($request->video_price[$key]) ? $request->video_price[$key] : null,

                ];
            }
            $row->{$this->hasManyRelationSubscription}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleSubscription . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteSubscription);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $dataReArrange      =       $this->getEditViewData($id);
        return  View($this->editRouteSubscription)->with(['data' => $dataReArrange]);
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
            if ($row->save()) {
                $translations = [];

                foreach ($request->name as $key => $value) {

                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeySubscription => $row->id,
                        'language_id' => $key,
                        'name' => $value,
                        'validity' => $request->validity[$key],
                        'price' =>isset($request->price[$key]) ? $request->price[$key] : null,
                        'video_price' => isset($request->video_price[$key]) ? $request->video_price[$key] : null,
                        'songs_service' => $request->songs_service[$key],
                        'video_service' =>$request->video_service[$key],
                        'subscription_type' =>$request->subscription_type[$key],
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
        Session::flash('success', $this->titleSubscription . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteSubscription);
    }

    /**
     * View Record
     */

     public function view($id)
     {
        $dataReArrange      =       $this->getEditViewData($id);
         return  View($this->viewRouteSubscription)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteSubscription)
            ->with('success', $this->titleSubscription . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $db = new MainModel();
        $db->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleSubscription . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }

    public function getEditViewData($id){
        $db             = new MainModel();
        $dataSubscription           = $db::with($this->hasManyRelationSubscription)->where('id', $id)->first();

        if (!$dataSubscription) {
            return abort(403);
        }
        $dataReArrangeSubscription['recordId'] = $dataSubscription->id;
        if (!empty($dataSubscription[$this->hasManyRelationSubscription])) {
            foreach ($dataSubscription[$this->hasManyRelationSubscription] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrangeSubscription[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return $dataReArrangeSubscription;
    }
}
