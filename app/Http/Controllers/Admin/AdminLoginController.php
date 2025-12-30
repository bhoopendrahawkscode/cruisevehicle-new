<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Http\Requests\UpdateMobileRequest;
use App\Http\Requests\VerifyMobileOtpCheck;
use Illuminate\Http\Request;

use Hash, Config, Session, Redirect, URL, Auth;

use App\Models\User;
use App\Models\UsersOtp;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\SendPasswordRequest;
use App\Http\Requests\SaveResetPasswordRequest;
use App\Services\GeneralService;
use Illuminate\Support\Facades\DB;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Models\Admin;
use App\Traits\HandleAdminLogin;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends BaseController
{
    use HandleAdminLogin;
    protected $passwordError;
    protected $passwordValidation;
    protected $validPasswordErrorMessage;
    protected $dashboardRoute;

    protected $selectCountryName;
    public function __construct()
    {

        parent::__construct();
        $this->passwordValidation = Config::get('constants.PASSWORD_VALIDATION');
        $this->validPasswordErrorMessage = trans("messages.ValidPasswordErrorMessage");
        $this->selectCountryName = "concat(dial_code, ' (', name,')') as full_name";
        $this->dashboardRoute = 'admin/dashboard';
    }

    public function showLoginForm()
    {

        if (Auth::guard('admin')->check()) {
            return Redirect::to($this->dashboardRoute);
        }
        return view('admin.login.admin-login');
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $user = User::where('email', $request->username)->first();

        // Check if the user exists
        if (!$user) {
            return redirect()->back()->withInput()->with('error', trans("messages.INVALID_CREDENTIALS"));
        }

        if ($user->status == 0 || $user->status == 'Inactive') {
            return redirect()->back()->withInput()->with('error', trans("messages.Account_Disabled"));
        }
        return $this->oneWayLogin($request);
        // if (GeneralService::getSettings('two_factor_authentication') == 'No') {
        //     return $this->oneWayLogin($request);

        // } elseif (GeneralService::getSettings('two_factor_authentication') == 'Yes') {
        //     $request->validate([
        //         'username' => 'required',
        //         'password' => 'required',
        //     ]);

        //     return $this->towWayLogin($request);
        // }
    }


    public function oneWayLogin($request)
    {
        $remember_me = $request->has('remember');

        if (!$this->attemptLogin($request, $remember_me)) {
            $this->handleFailedLogin($request, $remember_me);
            Session::flash('error', trans("messages.INVALID_CREDENTIALS"));
            return Redirect::back()->withInput();
        }

        if ($remember_me) {
            $this->setRememberMeCookies($request);
            if (!$this->hasAdminRole()) {

                $this->handleInvalidRole($request);
                return redirect()->back()->withInput();
            }
        } else {
            $this->clearCookies();
        }

        $this->updateDeviceToken();
        $request->session()->put('last_login_at', now());
        return Redirect::to($this->dashboardRoute)->with('info', trans("messages.youAreLoggedIn"));
    }


    public function towWayLogin($request)
    {

        $admin = User::where('email', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Generate OTP
            $otp = random_int(100000, 999999);

            // Save OTP to session or database
            Session::put('admin_otp', $otp);
            Session::put('admin_id', $admin->id);
            Session::put('admin_email', $request->username);
            Session::put('admin_pwd', $request->password);
            return Redirect::to('admin/mobile-otp')->with('info', 'Please enter your mobile for verification');
        }

        return back()->withErrors(['username' => 'Invalid credentials.']);
    }


    public function MobileOtp()
    {
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        return View('admin.login.mobile-otp', compact('phonecode'));
    }


    public function sendMobileOtp(Request $request)
    {
        try {

            $request->country_code = str_replace('+', '', $request->country_code);
            $userId  = Session::get('admin_id');
            $alreadyExists  = User::where(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no])->where('id', '!=', $userId)->first();
            if (!empty($alreadyExists)) {
                Session::flash('error', trans("messages.Mobile_No_Already_Exists"));
                $this->goToRedirect();
            }

            $alreadyExists  = User::where(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no])->where('id', '=', $userId)->first();
            if (empty($alreadyExists)) {
                Session::flash('error', trans("Invalid Mobile Number"));
                return redirect()->back()->withInput();
            }

            return $this->resendMobileOtp($request->country_code, $request->mobile_no);
        } catch (\Throwable $e) {

            if (env('APP_DEBUG')) {
                $message  = $e->getLine() . ' >> ' . $e->getMessage();
                Session::flash('error', $message);
            }
            CommonService::createExceptionLog($e);
            return redirect()->back()->withInput();
        }
    }

    public function goToRedirect()
    {
        return redirect()->back()->withInput();
    }



    public function resendMobileOtp($country_code, $mobile_no)
    {
        try {
            $otp  =  GeneralService::getGeneralOtp($country_code, $mobile_no);
            session(['otp' => $otp]);
            return Redirect::to('admin/check-mobile-otp')->with('info',  trans("messages.success") . ' Your otp is : ' . $otp);
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                echo $message  = $e->getLine() . ' >> ' . $e->getMessage();
                Session::flash('error', $message);
            }
            CommonService::createExceptionLog($e);
        }

        return redirect()->back()->withInput();
    }


    public function checkMobileOtp()
    {
        $userId   =   Session::get('admin_id');
        $userData  = User::where('id', '=', $userId)->first();
        $phonecode = DB::table('countries')->select('dial_code', 'id', DB::raw($this->selectCountryName))->orderBy('name')->pluck('full_name', 'dial_code')->toArray();
        $phonecode = ['' => __(Constant::SELECT_COUNTRY_CODE)] + $phonecode;
        $otp = Session::get('otp');
        return View('admin.login.check-mobile-otp', compact('phonecode', 'userData','otp'));
    }

    public function verifyMobileOtp(Request $request)
    {
        try {
            $userId         =   Session::get('admin_id');
            $request->country_code = str_replace('+', '', $request->country_code);
            $alreadyExists  = UsersOtp::where(['mobile_no' =>  $request->country_code . $request->mobile_no, 'otp' => $request->otp])->where('expired_at', '>=', time())->first();
            if (empty($alreadyExists)) {
                Session::flash('error', trans("messages.InvalidOtp"));
                return redirect()->back()->withInput();
            }

            User::where('id', $userId)->update([
                'mobile_verified' => 1,
                'country_code' => $request->country_code, 'mobile_no' => $request->mobile_no
            ]);


            $remember_me = 1;
            $admin_email         =   Session::get('admin_email');
            $admin_pwd         =   Session::get('admin_pwd');
            Auth::guard('admin')->attempt(['email' => $admin_email, 'password' => $admin_pwd, 'status' => 1], $remember_me);

            User::where('id', $userId)->update(['device_token' => time()]);
            return Redirect::to($this->dashboardRoute)->with('info', trans("messages.youAreLoggedIn"));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $message  = $e->getLine() . ' >> ' . $e->getMessage();
                Session::flash('error', $message);
            }
            CommonService::createExceptionLog($e);
            return redirect()->back()->withInput();
        }
    }


    public function forgotPassword()
    {
        if (Auth::guard('admin')->check()) {
            return Redirect::to($this->dashboardRoute);
        }
        return View('admin.login.forget_password');
    }

    /**
     * Function is used to send email for forgot password process
     *
     * @param null
     * @return url.
     */
    public function sendPassword(SendPasswordRequest $request)
    {

        $errorMsg   =   '';

        $email        =    $request->email;
        $userDetail    =    User::where('email', $email)->whereIn('role_id', [2, 3,6])->first();
       
        if (!empty($userDetail)) {

            if ($userDetail->status == 'Active') {
                $forgot_password_validate_string    =     base64_encode($userDetail->email . "*" . time() + 900);

                $email                 =  $userDetail->email;
                $full_name            =  $userDetail->full_name;
                $route_url          =  URL::to('admin/reset-new-password/' . $forgot_password_validate_string);

                $data = [
                    'replaceData' => [$full_name, $route_url],
                    'email' => $email, 'email_type' => 'forgot_password'
                ];

                try {
                    dispatch(new SendEmailJob($data));
                    Log::info('Email job dispatched successfully.', ['data' => $data]);
                } catch (\Exception $e) {
                    $e->getMessage();
                }

                return redirect()->route('forgotPassword')
                    ->with('success', trans("messages.FORGOT_PASSWORD_EMAIL_SENT"));
            } else {
                $errorMsg = trans("messages.Account_Disabled");
            }
        } else {
            $errorMsg   =  trans("messages.Email_Not_Register");
        }
        return redirect()->route('forgotPassword')->with('error', $errorMsg);
    }

    /**
     * Function is used for reset password
     *
     * @param $validate_string as validator string
     *
     * @return view page.
     */
    public function resetPassword($validate_string = null)
    {
        if (Auth::guard('admin')->check()) {
            return Redirect::to($this->dashboardRoute);
        }

        $msg  = trans("messages.linkExpired");
        if ($validate_string != "" && $validate_string != null) {
            $validate_string     =    base64_decode($validate_string);
            if (str_contains($validate_string, '*')) {
                $validate_string    =    explode("*", $validate_string);
                if ($validate_string[1] > time()) {
                    $userDetail    =    User::where('status', '1')
                        ->where([
                            'email'     => $validate_string[0],

                        ])->whereIn('role_id', [2, 3,6])
                        ->first();
                    if (!empty($userDetail)) {
                        return view(
                            'admin.login.reset_password',
                            ['validate_string' => base64_encode($validate_string[0])]
                        );
                    }
                } else {
                    $msg  = trans("messages.linkExpired");
                }
            }
        }
        return redirect('/admin/login')->with('error', $msg);
    }


    /**
     * Function is used for save reset password
     *
     * @param $validate_string as validator string
     *
     * @return view page.
     */
    public function saveResetPassword(SaveResetPasswordRequest $request)
    {

        $newPassword        =    $request->new_password;
        $validate_string_me    =    $request->validate_string;
        $user_data = User::where('email', base64_decode($validate_string_me))->first();

        if (!empty($user_data) && Hash::check($request->new_password, $user_data->password)) {

            $request->session()
                ->flash('error', trans('messages.newPasswordDifferentFromCurrentPassword'));
            return back()->withInput();
        }


        User::where('email', base64_decode($validate_string_me))
            ->update(array(
                'device_token' => '',
                'password' => Hash::make($newPassword),
                'password_changed_at' =>  now(),
            ));
        return redirect()->route('login')->with('success', trans('messages.Password_Success'))->withInput();
    }
}
