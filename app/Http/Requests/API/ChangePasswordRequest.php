<?php

namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;
use Config,Hash,Auth;
use App\Models\User;
class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'new_password' => Config::get('constants.PASSWORD_VALIDATION'),
        ];
    }


    protected function withValidator($validator) //PGupta
    {   // PGupta custom validation message
        if (!Hash::check($this->input('current_password'),Auth::user()->password)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('current_password',
                __("api.currentPasswordDoesNotMatched"));
            });
        }
    }
}
