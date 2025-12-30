<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Models\UserNotification;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Validation\Rule;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Http\Request;
use  Hash, DB,  Config, Validator, Session, Redirect,  Auth;
use App\Http\Requests\SendNotificationRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Models\UserPermission;
use App\Models\NotificationHistory;
use App\Services\CommonService;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
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
    protected $somethingWentWrongMsg, $allowSubAdminRole;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->paginate = GeneralService::getSettings('pageLimit');
        $this->passwordError = Config::get('constants.PASSWORD_ERROR');
        $this->passwordValidation = Config::get('constants.PASSWORD_VALIDATION');
        $this->phoneField = 'mobile_no.phone_unick';
        $this->phoneFieldError =  'Phone number already exists.';
        $this->zeroZero = Constant::ZERO_ZERO;
        $this->fullFull = Constant::FULL_FULL;
        $this->selectCountryName = "concat(dial_code, ' (', name,')') as full_name";
        $this->requiredMin2Max30 =  Config::get('constants.REQUIRED_MIN_2_MAX_30');
        $this->imageFolder = Config::get('constants.USER_FOLDER');
        $this->notificationManagement   =  __('messages.NotificationsManagement');
        $this->somethingWentWrongMsg = trans("messages.somethingWentWrong");
        $this->timeZoneList = Constant::TIMEZONES;
        \View::share([
            'section' =>   __('messages.Sub-AdminManagement'),
        ]);
    }



    public function listUsers(Request $request)
    {
        $DB   =   User::whereHas('roles', function ($q) {
            $q->where('roles.slug', 'user');
        });
        $fieldsToSearch =   array('status' => '=', 'id' => '!=', '*full_name-username-email-id-mobile_no*' => '*like-like-like-like-like*');
        $searchVariable                =    $request->all();
        $extraConditions               =    [];
        if (isset($searchVariable['type']) && $searchVariable['type'] == 1) {
            $extraConditions = [['subscription_general', '!=', null]];
        } else if (isset($searchVariable['type']) && $searchVariable['type'] == 2) {
            $extraConditions = [['subscription_meditation', '!=', null]];
        }


        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at', $extraConditions);
        extract($output);
        $statusChangeUrl        =    'admin/users/status/';

        return  View('admin.user.listUser', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }



    public function status($id, $value)
    {
        $user = User::find($id);

        if($value=='0'){
            DB::table('oauth_access_tokens')
            ->where('user_id', $id)
            ->update(['revoked' => true]);
        }
        $user->update(['status' => $value, 'auth_token' => '']);

        Session::flash('success', trans("messages.statusUpdated"));
        return CommonService::redirectStatusChange(Redirect::back());
    }



    public function pushNotification()
    {
        \View::share([
            'section' =>  $this->notificationManagement,
        ]);
        $userType = array(1 => trans('messages.allUser'), 2 => trans('messages.anyParticularUser'));
        $selected_user_type = (Session::has('selected_user_type')) ? Session::get('selected_user_type') : '';
        $selected_user_id = '';
        if (Session::has('selected_user_id')) {
            $implode_selected = Session::get('selected_user_id');
            if (is_array($implode_selected)) {
                $selected_user_id = implode(',', $implode_selected);
            }
        }

        return  View('admin.user.push_notification', compact('userType', 'selected_user_type', 'selected_user_id'));
    }

    public function ListNotificationHistory(Request $request)
    {
        \View::share([
            'section' =>  $this->notificationManagement,
        ]);
        $DB                            =   NotificationHistory::query();
        $fieldsToSearch              =   [ 'status' => '=','title' => 'like'];
         $searchVariable                =    $request->all();
         $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
         extract($output);
         $statusChangeUrl        =    '';
         $view           =       'admin.user.notification_history';
         return view($view, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));



    }
    public function ListNotification()
    {
        \View::share([
            'section' =>  $this->notificationManagement,
        ]);
        $DB                     =   UserNotification::query();
        $searchVariable         =   array();
        $searchData             =   Input::all();
        $type                   =   $searchData['type'];
        if ((Input::all()) || isset($searchData['display']) || isset($searchData['page'])) {

            if (!empty($searchData['title'])) {
                $DB->where('title', 'like', '%' . $searchData['title'] . '%');
                $searchVariable =   array_merge($searchVariable, array('title' => $searchData['title']));
            }
            $output = addDateFilter($searchData, $searchVariable, 'created_at', $DB);
            extract($output);
        }

        $sortBy                 =   (Input::get('sortBy')) ? Input::get('sortBy') : 'user_notifications.created_at';
        $order                  =   (Input::get('order')) ? Input::get('order')   : 'DESC';

        if (isset($_GET['type']) && $_GET['type'] == 'sent') {
            $DB->select('user_notifications.*', 'users.full_name')->where('sent_by_admin', '=',  1);
            $DB->leftJoin('users', 'users.id', '=', 'user_notifications.user_id');
            $view           =       'admin.user.list_notification_sent';
        } else {
            $DB->select('user_notifications.*')->where('user_id', '=',  Auth::user()->id);
            $view           =       'admin.user.list_notification';
        }
        if (Input::get('sortBy')) {
            $DB->orderBy($sortBy, $order);
        } else {
            $DB->orderByRaw('user_notifications.created_at desc');
        }
        $result = $DB->paginate($this->paginate);
        $allIds            =    [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $allIds[]  = $row->id;
            }
        }

        $complete_string        =   Input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends(Input::all())->render();
        DB::table('user_notifications')->whereIn('id', $allIds)->update(['status' => 1]);

        return  View($view, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'type'));
    }

    public function delete($Id)
    {
        // onlySubAdmin can be deleted.
        $DB = User::find($Id);
        if ($DB) {
            $DB->roles()->detach();
            $result =  $DB->where('id', $Id)->where('role_id', 3)->delete();
            if ($result) {
                UserPermission::where('user_id', $Id)->delete();
            }
        }
        return redirect()->back()
            ->with('success', trans("messages.subAdminDeleted"));
    }


    public function sendNotification(SendNotificationRequest $request)
    {

        \View::share([
            'section' =>   $this->notificationManagement
        ]);
        $allUSer = User::select('id', 'device_token', 'device_type')->where('notification_status', 1)->where('status', 1);
        if ($request->user_type == 1) {
            $allUSer =  $allUSer->where('role_id', 1);
        } elseif ($request->user_type == 2) {
            $allUSer =   $allUSer->whereIn('id', $request->user_id);
        } elseif ($request->user_type == 3) {
            $allUSer =   $allUSer->where('role_id', 3);
        } else {
            $allUSer =  $allUSer->where('id', $request->user_id);
        }
        $allUSer =  $allUSer->get();
        if (count($allUSer) > 0) {

            NotificationHistory::create([
                'title' => $request->title,
                'description' => $request->description,
                'send_to' => ($request->user_type == 1) ? "All Users" : "Specific User"
            ]);
            $temp = [];
            foreach ($allUSer as $val) {

                $temp['user_id']        = $val->id;
                $temp['title']          = $request->title;
                $temp['description']    = $request->description;
                $temp['type']            = 'AdminNotification';
                $temp['sent_by_admin']            = 1;

                Cache::forget('get_notification_count_' . $val->id . '');
                try {

                    dispatch(new SendPushNotificationJob($val, $temp));
                } catch (\Exception $exception) {

                    file_put_contents('notificationLog.txt', 'Date & Time : ' . date('Y-m-d H:i:s') . "\n\nRequest : {" . print_r(json_encode($val), true) . "}\n\n\n", FILE_APPEND);
                }
            }
        }

        Session::forget('selected_user_type');
        Session::forget('selected_user_id');
        Session::flash('success', trans("messages.notificationSent"));


        return Redirect::to('admin/push-notification');
    }




    /**
     * Add Sub admin
     */



    public function updateCss(Request $request)
    {
        $row = Setting::findOrFail(1);
        if ($request->type == 'primaryColor') {
            $row['primaryColor']    = $request->value;
        } elseif ($request->type == 'secondaryColor') {
            $row['secondaryColor']    = $request->value;
        }
        if ($row->save()) {
            Cache::put('settingsCache', $row);
        }
        echo 200;
        die;
    }

    public function viewUser($id)
    {
        \View::share([
            'section' =>   __('messages.UserManagement'),
        ]);
        $data = User::where('users.id', $id)
            ->select(
                'users.*',
            )
            ->first();

        return  View('admin.user.view', compact('data'));
    }

    public function addUser()
    {
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        return view('admin.user.user_add', compact('phonecode'));
    }

    public function editUser(User $user)
    {

        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        return view('admin.user.user_edit', compact('user', 'phonecode'));
    }

    public function saveUser(Request $request)
    {
        try {
            $input = $request->only('first_name', 'last_name', 'email', 'username', 'password', 'confirm_password', 'country_code', 'mobile_no');
            Validator::extend('phone_unick', function () use ($request) {
                $request->country_code    =    str_replace('+', '', $request->country_code);
                $usersData = User::where('mobile_no', $request->mobile_no)->first();

                if (is_object($usersData)) {
                    return false;
                }
                return true;
            });

            $validate   =   [
                'first_name' => $this->requiredMin2Max30,
                'last_name' => $this->requiredMin2Max30,
                'email' => 'required|email|unique:users,email|min:2|max:100',
                'username' => 'required|min:5|max:30|unique:users,username',
            ];
            if (Constant::ALLOW_SUB_ADMIN_PHONE_NO) {
                $validate       =   array_merge($validate, ['country_code' => 'required', 'mobile_no'  => 'required|digits_between:6,12|numeric|phone_unick']);
            }
            if (Constant::ALLOW_SUB_ADMIN_PASSWORD_CREATE) {
                $validate       =   array_merge($validate, [
                    'password' => $this->passwordValidation,
                    'confirm_password' => array_merge(['same:password'], $this->passwordValidation)
                ]);
            }
            $validator =   Validator::make(
                $input,
                $validate,
                [
                    $this->phoneField => $this->phoneFieldError,
                    'password.required'                 => trans('messages.passwordRequired'),
                    'confirm_password.required'     => trans('messages.confirmPasswordRequired'),
                    'confirm_password.same'                 => trans('messages.passConfirmNewPassNotMatched'),
                    "password.regex"                =>    $this->passwordError,
                    "confirm_password.regex"                =>    $this->passwordError
                ]
            );
            if ($validator->fails()) {
                $validator->messages();
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $input['password'] = Hash::make($request->password);

            DB::beginTransaction();

            $user = User::create($input);
            $user->roles()->attach([1]);
            DB::commit();
            return redirect()->route('admin.listUsers')->with(Constant::SUCCESS, trans("messages.userAdded"));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return Redirect::back()->withInput()->with(Constant::ERROR, $this->somethingWentWrongMsg);
        }
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            $input = $request->only('first_name', 'last_name', 'email', 'username', 'country_code', 'mobile_no');
            $id = $user->id;
            Validator::extend('phone_unick', function () use ($id, $request) {
                $request->country_code    =    str_replace('+', '', $request->country_code);
                $usersData = DB::table('users')->where('id', '!=', $id)->where('mobile_no', $request->mobile_no)->first();
                if (is_object($usersData)) {
                    return false;
                }
                return true;
            });

            $validate = [
                'first_name' => $this->requiredMin2Max30,
                'last_name' => $this->requiredMin2Max30,
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('users', 'email')->ignore($id), // $userId is the ID of the current user being updated
                ],
                'username' => [
                    'required', 'min:5', 'max:30', Rule::unique('users', 'username')->ignore($id),
                ]
            ];

            $validator    =   Validator::make(
                $input,
                $validate,
                [
                    $this->phoneField => $this->phoneFieldError

                ]
            );

            if ($validator->fails()) {
                $validator->messages();
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            DB::beginTransaction();
            $user->update($input);
            DB::commit();
            return redirect()->route('admin.listUsers')->with(Constant::SUCCESS, trans("messages.userUpdated"));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return Redirect::back()->withInput()->with(Constant::ERROR, $this->somethingWentWrongMsg);
        }
    }

    public function validateUserName(Request $request)
    {
        if ($request->username) {
            $query = User::where('username', $request->username);
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

    public function deleteUser(Request $request)
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
