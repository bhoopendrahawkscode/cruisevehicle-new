<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;

use App\Http\Requests\MediaManagerRequest;
use App\Models\MediaManager;
use App\Services\CommonService;
use App\Services\ImageService;
use App\Services\UploadHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class MediaManagerController extends BaseController
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
        $this->listRoute = 'admin.mediamanager.list';
        $this->imageFolder = Config::get('constants.MEDIA_FOLDER');
        $this->successName = 'MediaManager';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.media_manager'),
        ]);
    }

    public function  getAny()
    {
        return  new UploadHandler();
    }

    public function list(Request $request)
    {
        $DB                            =   MediaManager::query();
        $fieldsToSearch              =   ['*name*' => '*like*'];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        return view('admin.mediamanager.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    public function getImages(Request $request)
    {
        if ($request->ajax()) {
            $media = MediaManager::paginate(8);
            return view('admin.mediamanager.grid-images', compact('media'))->render();
        }
    }



    public function Update(MediaManagerRequest $request,  MediaManager $mediamanager)
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
            $mediamanager->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }



    public function delete(MediaManager $mediamanager)
    {
        try {
            DB::beginTransaction();
            $mediamanager->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function updateMediaName(MediaManager $mediamanager, MediaManagerRequest $request)
    {
        try {
            DB::beginTransaction();
            $mediamanager->update(['name' => $request->name]);
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->successName . __('messages.recordUpdated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function validateMediaName(Request $request)
    {
        if ($request->name) {
            $query = MediaManager::where('name', $request->name);
            if ($request->id) {
                $query->where('id', '!=', $request->id);
            }
            $user = $query->first();
            if ($user) {
                return "false";
            } else {
                return "true";
            }
        }
        return "true";
    }
}
