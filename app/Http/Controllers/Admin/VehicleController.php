<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\VehicleRequest;
use App\Models\Brand;
use App\Models\EngineCapacity;
use App\Models\FuelType;
use App\Models\CarModel;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class VehicleController extends BaseController
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
        $this->listRoute = 'admin.vehicle.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName ='Vehicle';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.vehicle'),
            'brands' => ['' => __('messages.select_brand')]+Brand::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
            'models'=> ['' => __('messages.select_models')]+CarModel::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
            'fuel_types'=> ['' => __('messages.select_fuel_types')]+FuelType::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
            'engine_capacity'=> ['' => __('messages.select_engine_capacity')]+EngineCapacity::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
            'transmission_types'=> ['' => __('messages.select_transmission_types')]+TransmissionType::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray()


        ]);
    }

    public function list(Request $request){
       $DB                            =   Vehicle::query();
       $DB= $DB->with(['brand', 'model', 'fuelType', 'engineCapacity', 'transmissionType']);
        $fieldsToSearch              =   ['status' => '=','*owner_name*' => '*like*'];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        return view('admin.vehicle.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    public function add()
    {
        return view('admin.vehicle.add');
    }

    public function edit(Vehicle $vehicle)
    {
        $model = $vehicle;
        return view('admin.vehicle.edit',compact('model'));
    }

    public function view(Vehicle $vehicle)
    {
        $result = Vehicle::with(['CarModel', 'fuelType', 'engineCapacity', 'InsuranceCompany', 'brand', 'CoverType', 'transmissionType','user'])
                              ->findOrFail($vehicle->id);
        return view('admin.vehicle.view',compact('result'));
    }


    public function save(Request $request){
           try {
            DB::beginTransaction();
            $input = $request->all();
            print_r($input);die;
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
            Vehicle::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

     public function Update(Request $request,  Vehicle $vehicle)
    {
        dd($request->all());
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
            $vehicle->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

        public function delete(Vehicle $vehicle)
        {
         try {
            DB::beginTransaction();
             $vehicle->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }




}
