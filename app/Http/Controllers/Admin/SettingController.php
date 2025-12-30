<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use  Hash, DB,  Config, Validator, Session, Redirect,  Auth;
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\Cache;
use App\Services\CommonService;
use App\Http\Controllers\Admin\BaseController;
use App\Services\ImageService;
use App\Constants\Constant;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends BaseController
{

    public function __construct()
    {
		parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolder = Config::get('constants.LOGO_FOLDER');
        $this->settingDateFormatList = Config::get('constants.SETTING_DATE_FORMAT_lIST');
        $this->UploadServerType = Config::get('constants.UPLOAD_SERVER_TYPE');
        $this->isMaintenanceMode = Config::get('constants.IS_MAINTENANCE_MODE');
        \View::share([
            'section' =>   __('messages.SettingsManagement'),
        ]);
    }

    public function index()
    {

        $UploadServerType = $this->UploadServerType;
        $settingDateFormatList = $this->settingDateFormatList;
        $isMaintenanceModeType = $this->isMaintenanceMode;
        $imagePath = $this->imageFolder;
        $settings = Setting::first();
        return view('admin.settings.list', compact('settings','imagePath','settingDateFormatList','UploadServerType','isMaintenanceModeType'));
    }

    public function save(SettingRequest $request)
    {

       // Retrieve the validated input data...
        $validated = $request->validated();

        if(!$validated)
        {
            $error = __(Constant::ERROR_OCCURRED);
            return CommonService::redirectBackWithError($error);
        } else {
                    $row = Setting::first();
                    $row->header = $request->header;
                    $row->footer = $request->footer;
                    $row->websiteTitle = $request->websiteTitle;
                    $row->companyEmail = $request->companyEmail;
                    $row->companyPhone = $request->companyPhone;
                    $row->companyAddress = $request->companyAddress;
                    $row->dateFormat = $request->dateFormat;
                    $row->UploadServerType = 'Local';
                    $row->googleAnalytics = $request->googleAnalytics;
                    $row->thirdPartyJs = $request->thirdPartyJs;
                    $row->customCss = $request->customCss;
                    $row->isMaintenanceMode = 'Up';
                    $mode = 'Up';
                    $row->password_generation = 'System';
                    $row->two_factor_authentication = 'No';
                    $row->otp_verification_mode = 'Test';

                    if(!empty($request->logo_image)) {
                        $fileName = ImageService::fileUploadImage($request->logo_image, $row->logo, $this->imageFolder);
                        $row->logo    = $fileName;
                    }

                    if(!empty($request->favicon_image)) {
                        $fileName = ImageService::fileUploadImage($request->favicon_image, $row->favicon, $this->imageFolder);
                        $row->favicon    = $fileName;
                    }


                    if($row->save()){
                        Cache::put('settingsCache',$row);
                        Session::flash('success',trans("messages.settingUpdated"));
                        if($mode=='Down'){
                            Artisan::call('down');
                        }
                        return Redirect::to('admin/settings');
                    }else{
                        $error = __(Constant::ERROR_OCCURRED);
                        return CommonService::redirectBackWithError($error);
                    }
            }
    }





}
