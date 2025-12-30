<?php
namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'websiteTitle' => 'required|min:2|max:50'
        ];
    }





}



