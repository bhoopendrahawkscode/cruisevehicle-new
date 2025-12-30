<?php

namespace App\Http\Requests\API;

use App\http\Requests\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_code' => 'required',
            'mobile_no' => 'required'
        ];
    }


}
