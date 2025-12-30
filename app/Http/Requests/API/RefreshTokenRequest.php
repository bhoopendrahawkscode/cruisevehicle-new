<?php

namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'refreshToken' => 'required',
        ];
    }


}
