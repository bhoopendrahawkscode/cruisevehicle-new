<?php

namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;
use Config;
class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_code' => 'required',
            'mobile_no' => 'required',
            'otp' => 'required',
            'password' => Config::get('constants.PASSWORD_VALIDATION'),
        ];
    }


}
