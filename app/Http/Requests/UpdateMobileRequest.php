<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
class UpdateMobileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_code' => 'required|min:2|max:3',
            'mobile_no'  => [
                'required',
                'numeric',
                'digits_between:6,15'
            ]
        ];
    }

}
