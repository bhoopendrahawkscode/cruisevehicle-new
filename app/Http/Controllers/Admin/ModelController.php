<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\ModelRequest;
use App\Models\Brand;
use App\Models\Model;
use App\Models\CarModel;
use App\Models\Scopes\StatusScope;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class ModelController extends BaseController
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
        $this->listRoute = 'admin.model.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Model';



        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.model'),
            'brands' => ['' => __('messages.select_brand')]+Brand::whereStatus(true)
            ->orderBy('name')->pluck('name', 'id')->toArray(),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
        ]);
    }

    public function list(Request $request)
    {
        $db              =   CarModel::query();
        $fieldsToSearch  =   ['*name*' => '*like*', 'status' => '='];
        $searchVariable  =    $request->all();
        $output          =    getFilters(
                                new Input,
                                $request,
                                $searchVariable,
                                $fieldsToSearch,
                                $db,
                                'created_at'
                            );
        extract($output);
        $statusChangeUrl =    'admin/vehicle-management/model/status/';
        return view('admin.model.index', compact(
                'result',
                'searchVariable',
                'sortBy',
                'order',
                'query_string',
                'statusChangeUrl'
            ));
    }

    public function add()
    {

        return view('admin.model.add');
    }

    public function edit(CarModel $model)
    {

        return view('admin.model.edit', compact('model'));
    }


    public function save(ModelRequest $request)
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

            CarModel::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function Update(ModelRequest $request, CarModel $model)
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
            $model->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function delete(CarModel $model)
    {
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }


    public function status(CarModel $model, $status)
    {
        try {
            DB::beginTransaction();
            $model->update([
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
