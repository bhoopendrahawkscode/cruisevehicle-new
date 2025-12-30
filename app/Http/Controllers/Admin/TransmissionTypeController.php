<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\TransmissionTypeRequest;
use App\Models\TransmissionType;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class TransmissionTypeController extends BaseController
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
        $this->listRoute = 'admin.transmissiontype.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName ='Transmission Type ';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.transmission_type'),
        ]);
    }

    public function list(Request $request){
       $DB                            =   TransmissionType::query();
       $fieldsToSearch              =   ['*name*' => '*like*', 'status' => '='];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/vehicle-management/transmission-type/status/';
        return view('admin.transmissiontype.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function add()
    {
        return view('admin.transmissiontype.add');
    }

    public function edit(TransmissionType $transmissiontype)
    {
        $model = $transmissiontype;
        return view('admin.transmissiontype.edit',compact('model'));
    }
    public function view(TransmissionType $transmissiontype)
    {
        return view('admin.transmissiontype.view', compact('transmissiontype'));
    }


    public function save(TransmissionTypeRequest $request){
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
            TransmissionType::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

     public function Update(TransmissionTypeRequest $request,  TransmissionType $transmissiontype)
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
            $transmissiontype->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

        public function delete(TransmissionType $transmissiontype)
        {
         try {
            DB::beginTransaction();
             $transmissiontype->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
    public function status(TransmissionType $transmissionType, $status)
    {
        try {
            DB::beginTransaction();
            $transmissionType->update([
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
