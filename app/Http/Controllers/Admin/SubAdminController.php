<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use  Session, Redirect, URL;


class SubAdminController extends Controller
{
    protected $paginate;
    protected $passwordError_;
    protected $passwordValidation_;
    protected $phoneFieldError_;
    protected $selectCountryName;
    protected $requiredMin2Max30;
    protected $requiredMin2Max60;

    protected $phoneField;

    protected $imageFolder;
    protected $roles,$somethingWentWrongMsg_,$allowSubAdminRole,$timeZoneList;
    protected $subadmin_password_generation;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->imageFolder = Config::get('constants.USER_FOLDER');
        $this->allowSubAdminRole = ['' => __(Constant::SELECT_ROLE)] + Role::whereNotIn('slug', ['admin', 'user'])->pluck('name', 'id')->toArray();
        $this->paginate = GeneralService::getSettings('pageLimit');
        $this->passwordError_ = Config::get('constants.PASSWORD_ERROR');
        $this->passwordValidation_ = Config::get('constants.PASSWORD_VALIDATION');
        $this->phoneField = 'mobile_no.phone_unick';
        $this->phoneFieldError_ =  'Phone number already exists.';
        $this->selectCountryName = "concat(dial_code, ' (', name,')') as full_name";
        $this->requiredMin2Max30 =  Config::get('constants.REQUIRED_MIN_2_MAX_30');
        $this->requiredMin2Max60 =  Config::get('constants.REQUIRED_MIN_2_MAX_60');
        $this->somethingWentWrongMsg_ = trans("messages.somethingWentWrong");
        $this->timeZoneList = Constant::TIMEZONES;
        $this->subadmin_password_generation = GeneralService::getSettings('password_generation');


        $version_ = GeneralService::getSettings('version_');


        View::share([
            'section' =>   __('messages.Sub-AdminManagement'),
            'version' => $version_

        ]);
    }

    public function logout(Request $request)
    {
        Auth('admin')->logout();

        if ($request->session()->has('link')) {
            $request->session()->forget('link');
        }
        return redirect()->route('login')->with('success', trans("messages.logoutMessage"));
    }


    public function subadminLogin($uid)
	{

       Auth::guard('admin')->logout();
		$attempt =Auth::guard('admin')->loginUsingId($uid);
		if ($attempt) {
			User::where('id', Auth::guard('admin')->user()->id)->update(['device_token' => time()]);
			return Redirect::to('admin/dashboard')->with('info', trans("messages.youAreLoggedIn"));
		}
        return Redirect::back()->withInput();
	}

    public function showEditProfile()
    {
        View::share([
            'section' =>   __('messages.editProfile'),
        ]);

        $authId = Auth::user()->id;
        $authDetail = User::WHERE('id', $authId)->first();
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
       $timeZone =collect($this->timeZoneList)->prepend(Constant::SELECT_TIMEZONE,'');

        return view('admin.user.editProfile', compact('authDetail', 'phonecode','timeZone'));
    }


    public function UpdateProfile(UpdateProfileRequest $request)
    {
        $authId = Auth::user()->id;
        $user   = User::find($authId);
        ##
        if (!empty($request->image)) {
            $fileName = ImageService::fileUploadImage($request->image, $user->image, $this->imageFolder);
            ImageService::manipulateImage(
                Constant::OPERATION_TYPE,
                $request->image,
                $this->imageFolder,
                200,
                200,
                $fileName
            );
            $user->image    = $fileName;
            Auth::user()->image = $fileName;
        }
        ##// Update user profile
        list($firstName, $lastName) = $this->splitFullName($request->full_name);
        $user->full_name = $request->full_name;
        $user->first_name = $firstName;
        $user->last_name = $lastName;

        $user->email           = $request->email;
        $user->country_code    =    str_replace('+', '', $request->country_code);
        $user->mobile_no = $request->mobile_no;
        $user->timezone = $request->timezone;
        $user->save();
        return redirect()->route('edit-profile')
            ->with('success', trans('messages.profileUpdated'));
    }
    private function splitFullName($fullName)
    {
        $nameParts = explode(' ', $fullName);

        $firstName = array_shift($nameParts);
        $lastName = !empty($nameParts) ? implode(' ', $nameParts) : '';

        return [$firstName, $lastName];
    }

    public function ChangePassword()
    {

        View::share([
            'section' =>   __('messages.changePassword'),
        ]);
        return view('admin.user.change-password', compact([]));
    }

    public function updatePassword(UpdateAdminPasswordRequest $request)
    {
        $authId = Auth::user()->id;
        $profileDetails = User::where('id', $authId)->first();

        if (!empty($profileDetails)) {
            $old_password = $profileDetails->password;
            if (Hash::check($request->current_password, $old_password)) {

                $data_array = ['device_token' => '', 'password' => Hash::make($request->new_password),'password_changed_at' =>  now()];

               User::WHERE('id', $authId)->update($data_array);

                $request->session()
                    ->flash('success', trans('messages.passwordUpdated'));
            } else {
                $request->session()
                    ->flash('error', trans('messages.currentPasswordNotMatch'));
            }
            return back();
        }
    }


    public function listSubadmin(Request $request)
    {

        $DB     =   User::whereHas('roles', function ($q) {
            $q->where('roles.slug', 'sub_admin');
        });
        $fieldsToSearch              =   array('status' => '=', 'id' => '!=', '*full_name-username-email-id*' => '*like-like-like-like*');
        $searchVariable              =    $request->all();
        $searchVariable['id']    =    Auth::user()->id;
        if (empty($request->sortBy)) {
            $request->sortBy = 'created_at';
        }
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/sub-admin/status/';
        return  View('admin.user.listSubadmin', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    public function addSubadmin()
    {
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        $sub_admin_roles =  $this->allowSubAdminRole;
        $timeZone =collect($this->timeZoneList)->prepend(Constant::SELECT_TIMEZONE,'');
        $password_generation = $this->subadmin_password_generation;

        return  View('admin.user.subadmin_add', compact('phonecode', 'sub_admin_roles','timeZone','password_generation'));
    }
    /**
     * Save Sub admin
     */
    public function saveSubadmin(Request $request)
    {
        Validator::extend('phone_unick', function () use ($request) {
            $request->country_code    =    str_replace('+', '', $request->country_code);
            $usersData = DB::table('users')->where('mobile_no', $request->mobile_no)->first();

            if (is_object($usersData)) {
                return false;
            }
            return true;
        });

        $validate                   =   array(
            'full_name' => $this->requiredMin2Max60,
            'email' => 'required|email|unique:users,email|min:2|max:100',
        );

        $passwordMessage = [];
        if (Constant::ALLOW_SUB_ADMIN_PASSWORD_CREATE && $this->subadmin_password_generation==Constant::MANUAL_FACTOR) {
            $validate       =   array_merge($validate, [
                'password' => $this->passwordValidation_,
                'confirm_password' => array_merge(['same:password'], $this->passwordValidation_)
            ]);
            $passwordMessage = array( 'confirm_password.required'=> trans('messages.confirmPasswordRequired'),
                                        'confirm_password.same' => trans('messages.passConfirmNewPassNotMatched'));
        }
        $validator                  =   Validator::make(
            $request->all(),
            $validate,
            array(
                $this->phoneField => $this->phoneFieldError_,
                'password.required'                 => trans('messages.passwordRequired'),
                'confirm_password.required'     => trans('messages.confirmPasswordRequired'),
                 $passwordMessage,
                "confirm_password.regex"                =>    $this->passwordError_
            )
        );
        if ($validator->fails()) {
            $validator->messages();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $obj               = new User();
            $obj->role_id    = $request->role;



            list($firstName, $lastName) = $this->splitFullName($request->full_name);
            $obj->full_name = $request->full_name;
            $obj->first_name = $firstName;
            $obj->last_name = $lastName;


            $obj->email    = $request->email;
            $obj->country_code    = $request->country_code;
            $obj->mobile_no    = $request->mobile_no;
            $obj->timezone    = $request->timezone;


            $obj        =   GeneralService::getPassword($obj, $request);
            $emailData = [
                'replaceData' => [$obj->full_name, $obj->password, URL::to('admin/login')],
                'email' => $request->email, 'email_type' => 'subadmin_password'
            ];
            $obj->password      =   Hash::make($obj->password);
            GeneralService::sendSubAdminPassword($emailData);
            $obj->save();
            $userId             =   $obj->id;
            if (!$userId) {
                DB::rollback();
                return redirect()->back()->withInput()->with(constant::ERROR,$this->somethingWentWrongMsg_);
            }
            $obj->roles()->attach($request->role);
            $permissions = $obj->roles->first()->permissions->pluck('id')->toArray();
            $obj->permissions()->attach($permissions);
            return redirect()->route('admin.listSubadmin')->with(constant::SUCCESS,trans("messages.subAdminAdded"));
        }
    }
    /**
     * Edit Sub admin
     */
    public function editSubadmin($id)
    {

        $data = User::where('id', $id)->first();
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        $sub_admin_roles =  $this->allowSubAdminRole;
       $timeZone =collect($this->timeZoneList)->prepend(Constant::SELECT_TIMEZONE,'');
        return  View('admin.user.subadmin_edit', compact('data', 'phonecode', 'id', 'sub_admin_roles','timeZone'));
    }
    /**
     * Update Sub admin
     */
    public function updateSubadmin($id, Request $request)
    {
        Validator::extend('phone_unick', function () use ($id, $request) {
            $request->country_code    =    str_replace('+', '', $request->country_code);
            $usersData = DB::table('users')->where('id', '!=', $id)->where('mobile_no', $request->mobile_no)->first();
            if (is_object($usersData)) {
                return false;
            }
            return true;
        });

        $validate = array(
            'full_name' => $this->requiredMin2Max60,
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($id), // $userId is the ID of the current user being updated
            ]
        );
        //$validate = GeneralService::mergePhoneNumberValidation($validate);


        $validator                  =   Validator::make(
            $request->all(),
            $validate,
            array(
                $this->phoneField => $this->phoneFieldError_

            )
        );
        if ($validator->fails()) {

            $validator->messages();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $obj                = User::find($id);
        $obj->role_id    = $request->role;
        list($firstName, $lastName) = $this->splitFullName($request->full_name);
        $obj->full_name = $request->full_name;
        $obj->first_name = $firstName;
        $obj->last_name = $lastName;
        $obj->email    = $request->email;
        $obj->country    = $request->country_code;
        $obj->country_code    =    str_replace('+', '', $request->country_code);
        $obj->mobile_no = $request->mobile_no;
        $obj->timezone    = $request->timezone;

        $obj->save();
        $userId             =   $obj->id;


        if (!$userId) {
            DB::rollback();
            return back()->withInput()->with(constant::ERROR,$this->somethingWentWrongMsg_);
        }
        $obj->roles()->sync($request->role);
        $permissions = $obj->roles->first()->permissions->pluck('id')->toArray();
        $obj->permissions()->sync($permissions);
        return redirect()->route('admin.listSubadmin')->with(constant::SUCCESS,trans("messages.subAdminUpdated"));
    }


    public function status($id, $value)
    {

        $user = User::find($id);
        $user->update(['status' => $value, 'auth_token' => '']);
        return CommonService::redirectStatusChange(Redirect::back()->with('success', trans("messages.statusUpdated")));
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        DB::beginTransaction();
        $user->permissions()->detach();
        $user->roles()->detach();
        $is_delete = $user->delete();

        if ($is_delete) {
            DB::commit();
        } else {
            DB::rollback();
        }
        return redirect()->back()
            ->with(Constant::SUCCESS, trans("messages.userDeleted"));
    }



}
