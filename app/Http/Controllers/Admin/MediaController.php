<?php

namespace App\Http\Controllers\Admin;


use App\Models\MediaImage;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use  Hash, DB,  Config, Validator, Session, Redirect,  Auth;
use App\Http\Requests\UpdateMediaRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Cache;
use App\Models\UserPermission;
use App\Services\CommonService;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
class MediaController extends BaseController
{
    protected $paginate;
    protected $passwordError;
    protected $passwordValidation;
    protected $phoneFieldError;
    protected $zeroZero;
    protected $fullFull;
    protected $selectCountryName;
    protected $requiredMin2Max30;

    protected $phoneField;
    protected $imageFolder;
    protected $notificationManagement;

    public function __construct()
    {
		parent::__construct();
        $this->middleware('auth:admin');
        $this->paginate = GeneralService::getSettings('pageLimit');
        $this->imageFolder = Config::get('constants.MEDIA_FOLDER');
        $this->notificationManagement   =  __('messages.NotificationsManagement');
        \View::share([
            'section' =>   __('messages.Sub-AdminManagement'),
        ]);
    }

    /**
     * Function is used to logout admin user
     *
     * @param null
     *
     * @return view page.
     */


    public function index(Request $request)
    {
        $result = MediaImage::WHERE('status', 1)->get();
        \View::share([
            'section' =>   __('messages.MediaManagement'),
        ]);
        return  View('admin.media.index', compact('result'));
    } // end listUsers


  
    public function showEditMedia()
    {
        \View::share([
            'section' =>   __('messages.editMedia'),
        ]);

        $mediaDetail = MediaImage::WHERE('status', 1)->first();

        return view('admin.media.editMedia', compact('mediaDetail')); 
    }


    public function UpdateMedia(UpdateMediaRequest $request)
    {
        MediaImage::WHERE('status', 1)->first();
        $media   =  MediaImage::find(1);
        ##
        if(!empty($request->image)) {
            $fileName = ImageService::fileUploadImage($request->image, $media->logo, $this->imageFolder);
            ImageService::manipulateImage(
                Constant::OPERATION_TYPE,
                $request->image,
                $this->imageFolder,
                200,
                200,
                $fileName
            );
            $media->logo    = $fileName;
        }
        ##
        $media->save();
        return redirect()->route('edit-media')->with('success', trans('messages.mediaUpdated'));
    }

   
  
    public function status($id, $value)
    {
        DB::table('media_images')->where('id', $id)->update(['status' => $value, 'auth_token' => '']);
        Session::flash('success', trans("messages.statusUpdated"));
        return CommonService::redirectStatusChange(Redirect::back());
    }

    

}
