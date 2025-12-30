<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;
use Laravel\Passport\RefreshToken;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\models\User;
use App\Services\GeneralService;
use Auth;
class BaseController extends Controller
{
    protected $message;
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = JsonResponse::HTTP_OK)
    {
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $result,
        ];
        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error,$code = 422)
    {
        $response = [
            'status' => $code,
            'message' => $error,
			'data' => new \stdClass,
        ];
        return response()->json($response, 422);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendErrorToken($result,$error,$code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
    {
        $response = [
            'status' => $code,
            'message' =>$error,
            'data' => $result,
        ];
        return response()->json($response, JsonResponse::HTTP_CREATED);
    }
    public function doLogin($request){


        $username  = '';
        $pw  = '';
        if($request->loginType == 'SOCIAL'){

            $requestData = [
                $request->social_type => $request->social_id,
                'role_id' => 1,
            ];

            $username   = $request->social_type;
            $pw   = $request->social_id;

            // create account if it is not created

            $checkAlready  =   User::where($request->social_type,$request->social_id)->count();
            if($checkAlready == 0) {
                $requestData['referral_code'] =  GeneralService::generateRandomCode();
                User::create($requestData);
            }

        }else{
            $requestData = [
                'country_code' => $request->country_code,
                'mobile_no' => $request->mobile_no,
                'role_id' => 1,
                'status' => 1
            ];
        }

        $user = User::where($requestData)->first();
        if($user){
            Auth::login($user);
        }

        if($user == null && $request->loginType == 'REGULAR'){
            $this->message      =   trans('You need to register your account.');
            return $this->sendError($this->message);
        }


        if($request->loginType == 'REGULAR'){
            $username       =   $user->username;
            $pw       =   '11';
        }

       return $this->doLoginChild($username,$pw,$request);
    }
    public function doLoginChild($username,$pw,$request){

        Auth::user()->tokens()->each(function ($token) {
            if($token){
                $token->delete();
                $refreshToken   =   RefreshToken::where('access_token_id', $token->id)->first();
                $refreshToken->delete();
            }
        });
        if($request->loginType == 'REGULAR'){
            $tokenData = $this->generateLoginTokenFromUserName(Auth::user()->username);
        }else{
            $tokenData = $this->generateLoginTokenFromSocialId($username,$pw);
        }
        $otp                       =    '';
        if(Auth::user()->mobile_no == ''){
            $verificationStatus    =   'mobileNoUpdateRequired';
        }else{
            if($request->loginType == 'SOCIAL'){
                $verificationStatus    =   'passed';
            }else{
                $otp                   =  GeneralService::getUserOtp();
                $verificationStatus    =   'mobileNoVerificationRequired';
            }
        }
        return $this->sendResponse(new UserResource(Auth::user(),['tokenData'=> $tokenData,
        'verificationStatus'=>$verificationStatus,'otp'=>$otp]),
        trans('messages.CUSTOMER_LOGIN_MSG'));

    }

    public function generateLoginTokenFromUserName($username){

die('ghghfdhfdjh');
        $request = Request::create('oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            "username" => $username,
            "password" => "11",
            "scope" => '',
            "loginType" => "Regular",
            "key" =>"",
        ]);
        $result = app()->handle($request);
        dd($result);die;
        return  json_decode($result->getContent(), true);
    }

    public function generateLoginTokenFromSocialId($socialType,$socialId){

        $request = Request::create('oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            "username" => $socialType,
            "password" => $socialId,
            "scope" => '',
            "loginType" => "SOCIAL",
            "key" => "",
        ]);
        $result = app()->handle($request);
        return  json_decode($result->getContent(), true);
    }
}
