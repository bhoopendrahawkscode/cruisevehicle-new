<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\FuelTypeRequest;
use App\Models\FuelType;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class FuelTypeController extends BaseController
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
        $this->listRoute = 'admin.fueltype.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName ='Fuel Used ';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.fueltype'),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
        ]);
    }

    public function list(Request $request){
       $DB                            =   FuelType::query();
       $fieldsToSearch              =   ['*name*' => '*like*', 'status' => '='];

        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/vehicle-management/fuel-type/status/';
        return view('admin.fueltype.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function add()
    {
        return view('admin.fueltype.add');
    }

    public function edit(FuelType $fueltype)
    {
        $model = $fueltype;
        return view('admin.fueltype.edit',compact('model'));
    }


    public function save(FuelTypeRequest $request){
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
            FuelType::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

     public function Update(FuelTypeRequest $request,  FuelType $fueltype)
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
            $fueltype->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

        public function delete(FuelType $fueltype)
        {
         try {
            DB::beginTransaction();
             $fueltype->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
    public function status(FuelType $fuelType, $status)
    {
        try {
            DB::beginTransaction();
            $fuelType->update([
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
