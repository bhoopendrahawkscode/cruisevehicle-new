<?php
namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Config;
use App\Constants\Constant;

class UpdateAdminPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        define('PASSWORD_VALIDATION',Config::get('constants.PASSWORD_VALIDATION'));
        return [
            'current_password' => array_merge(PASSWORD_VALIDATION),
            'new_password' => array_merge(PASSWORD_VALIDATION,['different:current_password']) ,
            'confirm_password' =>  array_merge(PASSWORD_VALIDATION,['same:new_password']),
        ];
    }

}



