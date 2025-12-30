<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\ServiceProviderRequest;
use App\Models\ServiceProvider;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class ServiceProviderController extends BaseController
{

    protected $mainTable;
    protected $foreignKey;
    protected $translationFields;
    protected $listRoute;
    protected $imageFolder;
    protected $successName;
    protected $selectCountryName;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRoute = 'admin.serviceprovider.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Service Provider  Manager ';
        $this->selectCountryName = "concat(dial_code, ' (', name,')') as full_name";
        $this->addRouteServiceProvider = 'admin.serviceprovider.add';
        $this->listRouteServiceProvider = 'admin.serviceprovider.index';
        $this->addRouteServiceProvider = 'admin.serviceprovider.add';
        $this->editRouteServiceProvider = 'admin.serviceprovider.edit';
        $this->thumbServiceProvider  = '/thumb-';
        $this->mainTableServiceProvider  = 'service_providers';
        $this->mainTableSingularServiceProvider  = 'service_providers';
        $this->titleServiceProvider  = __('messages.servicePr');



        View::share([
            'listRoute' => $this->listRoute,
            'addRoute' => $this->addRouteServiceProvider,
            'title' => trans('messages.servicePr'),
            'formId' => "serviceproviderForm",
            'imageFolder' => $this->imageFolder,
            'title' => $this->titleServiceProvider,
            'saveRoute' => "admin.serviceprovider.save",
            'updateRoute' => "admin.serviceprovider.update",
            'listUrl' => "service-provider",
            'mainTable' => $this->mainTableServiceProvider,
            'formPath' => 'admin.serviceprovider.form',
            'addPermission' => "POST_MANAGEMENT_ADD",
            'editPermission' => "POST_MANAGEMENT_EDIT",
            'deletePermission' => "POST_MANAGEMENT_DELETE",
            'statusPermission' => "POST_MANAGEMENT_CHANGE_STATUS"
        ]);
    }

    public function list(Request $request)
    {
        $DB                            =   ServiceProvider::query();
       // $fieldsToSearch              =   ['*name*' => '*like*', 'status' => '='];
        $fieldsToSearch =   array('status' => '=', 'id' => '!=', '*name-mobile_no*' => '*like-like*');
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/service-provider/status/';
        return view('admin.service-provider.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function add()
    {
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;

        return view('admin.service-provider.add', compact('phonecode'));
    }

    public function edit(ServiceProvider $serviceprovider)
    {
        $model = $serviceprovider;
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        return view('admin.service-provider.edit', compact('model','phonecode'));
    }


    public function save(ServiceProviderRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolder);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolder,
                    150,
                    100,
                    $fileName
                );

                $input['image'] = $fileName;
            }
            ServiceProvider::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function Update(ServiceProviderRequest $request,  ServiceProvider $serviceprovider)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolder);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolder,
                    150,
                    100,
                    $fileName
                );

                $input['image'] = $fileName;
            }
            $serviceprovider->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function delete(ServiceProvider $serviceprovider)
    {
        try {
            DB::beginTransaction();
            $serviceprovider->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function status(ServiceProvider $serviceprovider, $status)
    {
        try {
            DB::beginTransaction();
            $serviceprovider->update([
                'status' => $status
            ]);
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.statusUpdated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
}
