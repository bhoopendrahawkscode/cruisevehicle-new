<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SupportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required|min:2',
        ];
    }


}



