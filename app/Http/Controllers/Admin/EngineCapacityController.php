<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\EngineCapacityRequest;
use App\Models\Brand;
use App\Models\EngineCapacity;
use App\Models\CarModel;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class EngineCapacityController extends BaseController
{

    protected $mainTable;
    protected $foreignKey;
    protected $translationFields;
    protected $listRoute;
    protected $imageFolder;
    protected $successName;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRoute = 'admin.enginecapacity.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName ='EngineCapacity';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.engine_capacity'),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
            'brands' => ['' => __('messages.select_brand')]+Brand::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
            'models'=> ['' => __('messages.select_models')]+CarModel::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    public function list(Request $request){
       $DB                            =   EngineCapacity::query()->with('brand','model');
       $fieldsToSearch              =   ['capacity' => 'like', 'status' => '='];

        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/vehicle-management/engine-capacity/status/';
        return view('admin.enginecapacity.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function add()
    {
        return view('admin.enginecapacity.add');
    }

    public function edit(EngineCapacity $enginecapacity)
    {
        $model = $enginecapacity;
        return view('admin.enginecapacity.edit',compact('model'));
    }


    public function save(EngineCapacityRequest $request){
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
            EngineCapacity::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

     public function Update(EngineCapacityRequest $request,  EngineCapacity $enginecapacity)
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
            $enginecapacity->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

        public function delete(EngineCapacity $enginecapacity)
        {
         try {
            DB::beginTransaction();
             $enginecapacity->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function status(EngineCapacity $enginecapacity, $status)
    {
        try {
            DB::beginTransaction();
            $enginecapacity->update([
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
