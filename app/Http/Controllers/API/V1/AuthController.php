<?php

namespace App\Http\Controllers\API\V1;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersOtp;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Constants\Constant;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\RefreshTokenRequest;
use App\Services\CommonService;
use App\Services\GeneralService;
use Config,DB,Auth;
use App\Services\ImageService;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends BaseController
{

    protected $message;
    protected $data ;

    public function __construct()
    {
       $this->data        		= 	new \stdClass;
    }
     /**
     * Register
     */

     public function preRegister(RegisterRequest $request)
     {
         try {
            $otp                            =       GeneralService::getGeneralOtp($request->country_code,
            $request->mobile_no);
            $this->data->otp                =    $otp;
            return $this->sendResponse($this->data, trans('api.success'));
         } catch (\Throwable $e) {
             $this->message = __(Constant::ERROR_OCCURRED);
             if(env('APP_DEBUG')){
                 $this->message  = $e->getMessage();
             }
             CommonService::createExceptionLog($e);
         }
         return $this->sendError($this->message);
    }

    public function register(RegisterRequest $request)
    {
    $fullName = $request->input('full_name');
    list($firstName, $lastName) = $this->splitFullName($fullName);

    // Generate a username from the full name
    $username = strtolower($firstName);

    // Ensure the username is unique
    $originalUsername = $username;
    $counter = 1;
    while (User::where('username', $username)->exists()) {
        $username = $originalUsername . $counter;
        $counter++;
    }

    DB::beginTransaction();
    try {
        $requestData = $request->all();
        $requestData['role_id'] = '1';
        $requestData['full_name'] = $fullName;
        $requestData['first_name'] = $firstName;
        $requestData['last_name'] = $lastName;
        $requestData['username'] = $username;
        $requestData['mobile_verified'] = 1;
        $requestData['password'] = bcrypt($request->password);

        $createdUser = User::create($requestData);
        $createdUser->roles()->attach([1]);

        DB::commit();

        Auth::loginUsingId($createdUser->id);
        if($request->email != ""){
            $emailData = [
                'replaceData' => [$request->full_name],
                'email' => $request->email, 'email_type' => 'welcome_email'
            ];
            try {
                dispatch(new SendEmailJob($emailData));
            } catch (\Exception $e) {
                echo  $e->getMessage();
            }
        }
        $tokenData = $this->generateLoginTokenFromUserName($createdUser->username);

        return $this->sendResponse(
            new UserResource(Auth::user(), ['tokenData' => $tokenData, 'verificationStatus' => "passed"]),
            trans('messages.USER_REGISTERED_SUCCESSFULLY')
        );
    } catch (\Throwable $e) {
        DB::rollback();

        $this->message = __(Constant::ERROR_OCCURRED);
        if (env('APP_DEBUG')) {
            $this->message = $e->getMessage();
        }
        CommonService::createExceptionLog($e);
    }

    return $this->sendError($this->message);
}





    private function splitFullName($fullName)
    {
        $nameParts = explode(' ', $fullName);

        $firstName = array_shift($nameParts);
        $lastName = !empty($nameParts) ? implode(' ', $nameParts) : '';

        return [$firstName, $lastName];
    }


    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        try {
            if($request->loginType == 'SOCIAL'){

                $alreadyExists = User::where([$request->social_type=>$request->social_id])->first();

                if(!empty($alreadyExists)){
                    if ($alreadyExists->status == 0 || $alreadyExists->status == 'Inactive') {
                        $this->message = trans('api.ACCOUNT_INACTIVE');
                        return $this->sendError($this->message);
                    }
                    Auth::login($alreadyExists);
                }

            }else{
                $alreadyExists = User::where('email', $request->email)->first();
                if(!empty($alreadyExists)){
                if ($alreadyExists && $alreadyExists->status == 0 || $alreadyExists->status == 'Inactive') {
                    $this->message = trans('api.ACCOUNT_INACTIVE');
                    return $this->sendError($this->message);
                }

            }
            }
           


                return $this->doLogin($request);

        } catch (\Throwable $e) {

            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getLine().' >> '.$e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }
    /**
     * Get access token from refresh token
    */
    public function UpdatePassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->sendError(__("User does not exist."));
            }
            if (Hash::check($request->new_password, $user->password)) {
                return $this->sendError(__("New password cannot be the same as the existing password."));
            }
            $user->update(['password' => bcrypt($request->new_password)]);

            return $this->sendResponse($this->data, __("Password changed successfully."));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }


    public function getAccessToken(RefreshTokenRequest $request){
        try {
            $requestData = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->input('refreshToken'),
                'client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            ];
            $refreshTokenRequest = Request::create('oauth/token', 'POST', $requestData);
            $result = app()->handle($refreshTokenRequest);
            $tokenData = json_decode($result->getContent(), true);

            if(isset($tokenData['error'])){
                return $this->sendError($tokenData['message']);
            }
            return $this->sendResponse($tokenData, trans('messages.NEW_ACCESS_TOKEN'));
        } catch (\Throwable $e) {
            $this->message = $e->getMessage();
        }
        return $this->sendError($this->message);
    }
    public function sendEmailOtp(Request $request)
    {
        try {

            $this->message      =   "An OTP was sent to your email id to verify your email";
            $otp = GeneralService::generateOtp();
            $userResult=User::where('email',$request->email)->first();
            if (!$userResult) {
                return $this->sendError(__("Email does not exist."));
            }
                User::where('email',$request->email)->update(['email_otp' =>$otp,'email_otp_expire'=>time()+60]);

                $emailData = [
                    'replaceData' => [$userResult->full_name,$otp],
                    'email' => $request->email, 'email_type' => 'email_otp'
                ];


            try {
                dispatch(new SendEmailJob($emailData));
            } catch (\Exception $e) {
                $this->message =   $e->getMessage();
            }
            $this->data->otp    =   $otp;
            return $this->sendResponse($this->data,$this->message);
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function verifyEmailOtp(Request $request)
    {
        try {
            // Attempt to find the user by email
            $user = User::where('email', $request->email)->first();


            if (!$user) {
                return $this->sendError(__("Email does not exist."));
            }

            // Check if the provided OTP matches and if it is not expired
            if ($request->otp != $user->email_otp || $user->email_otp_expire < time()) {
                return $this->sendError(__("Invalid or expired OTP, click on resend to get the latest OTP."));
            }

            $user->update([
                'email_verified' => '1'
            ]);

            return $this->sendResponse($user, "Email Verified Successfully.");

        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function changeLanguage(Request $request, $locale)
    {

        if (in_array($locale, ['en', 'fr'])) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }

    public function logout(Request $request){
        $userId         =   Auth::user()->id;
        DB::table('users')->where('id', $userId)->update(['auth_token' => '','device_type'=>'','device_token'=>'']);
        $request->user()->token()->revoke();
        return $this->sendResponse([], trans('messages.Successfully logged out'));
    }

    public function checkAuthStatus()
    {
        return response()->json(['status' => 'authenticated']);
    }



}

