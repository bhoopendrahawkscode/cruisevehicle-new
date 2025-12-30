<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\API\FuelRefillApiRequest;
use App\Models\FuelRefill;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class FuelRefillController extends BaseController
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
        $this->listRoute = 'admin.fuelrefill.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName ='Fuel Refill ';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.fuelrefill'),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
        ]);
    }

    public function list(Request $request){
       $DB                            =   FuelRefill::query()
    ->with(['brand', 'model', 'fuelType', 'engineCapacity', 'transmissionType', 'vehicle'])
    ->leftJoin('vehicles', 'fuel_refills.vehicle_id', '=', 'vehicles.id') // <-- add this
    ->select('fuel_refills.*');
       $fieldsToSearch              =   ['*name*' => '*like*', 'status' => '='];

        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'fuel_refills.created_at');
        extract($output);
        $statusChangeUrl        =    'admin/vehicle-management/fuel-refill/status/';
        return view('admin.fuelrefill.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function add()
    {
        return view('admin.fuelrefill.add');
    }

    public function edit(FuelRefill $fuelrefill)
    {
        $model = $fuelrefill;
        return view('admin.fuelrefill.edit',compact('model'));
    }


    public function save(FuelRefill $request){
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
            FuelRefill::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

     public function Update(FuelRefillApiRequest $request,  FuelRefill $fuelrefill)
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
            $fuelrefill->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

        public function delete(FuelRefill $fuelrefill)
        {
         try {
            DB::beginTransaction();
             $fuelrefill->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
    public function status(FuelRefill $fuelrefill, $status)
    {
        try {
            DB::beginTransaction();
            $fuelrefill->update([
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
