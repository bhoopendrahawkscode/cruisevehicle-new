<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;
use Laravel\Passport\RefreshToken;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\models\User;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Hash;
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
            $fullName = $request->input('full_name');
            list($firstName, $lastName) = $this->splitFullName($fullName);
        
            // Generate a username from the full name
            $username = strtolower($firstName);

            $requestData = [
                $request->social_type => $request->social_id,
                'role_id' => 1,
                'device_type' => $request->device_type,
                'device_token' => $request->device_token,
                'email' => $request->email,
            ];
            $requestData['full_name'] = $fullName;
            $requestData['first_name'] = $firstName;
            $requestData['last_name'] = $lastName;
            $requestData['username'] = $username;
            $username   = $request->social_type;
            $pw   = $request->social_id;

            // create account if it is not created

            $checkAlready  =   User::where($request->social_type,$request->social_id)->count();

            if($checkAlready == 0) {
                $createdUser= User::create($requestData);
                Auth::login($createdUser);
                $createdUser->roles()->attach([1]);
            }else{
                $user = User::where($request->social_type,$request->social_id)->first();
                $user->device_type = $request->device_type;
                $user->device_token = $request->device_token;
                $user->save();


            }

        }else{
            $user = User::where('email', $request->email)->first();
                if($user){
                    Auth::login($user);
                }

                if ($user && Hash::check($request->password, $user->password)) {
                    $userId = Auth::user()->id;
                    $user = User::find($userId);
                    $user->device_type = $request->device_type;
                    $user->device_token = $request->device_token;
                    $user->save();

                     } else {
                     $this->message = trans('api.INVALID_CREDENTIALS');
                return $this->sendError($this->message);

                }

                if($user == null && $request->loginType == 'REGULAR'){
                    $this->message      =   trans('You need to register your account.');
                    return $this->sendError($this->message);
                }
        }
        if($request->loginType == 'REGULAR'){
            $username       =   $user->username;
            $pw       =   '11';
        }

       return $this->doLoginChild($username,$pw,$request);
    }

    private function splitFullName($fullName)
    {
        $nameParts = explode(' ', $fullName);

        $firstName = array_shift($nameParts);
        $lastName = !empty($nameParts) ? implode(' ', $nameParts) : '';

        return [$firstName, $lastName];
    }
    public function doLoginChild($username,$pw,$request){

        if($request->loginType == 'REGULAR'){
            $tokenData = $this->generateLoginTokenFromUserName(Auth::user()->username);
        }else{

            $tokenData = $this->generateLoginTokenFromSocialId($username,$pw);
        }

        $verificationStatus    =   'emailVerificationRequired';


        return $this->sendResponse(new UserResource(Auth::user(),['tokenData'=> $tokenData,
        'verificationStatus'=>$verificationStatus]),
        trans('messages.CUSTOMER_LOGIN_MSG'));

    }

    public function generateLoginTokenFromUserName($username){

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
