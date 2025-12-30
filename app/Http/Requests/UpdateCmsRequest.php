<?php
namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Config;
class UpdateCmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        define("REQUIRED_MIN_2_MAX_500",'required|max:500|min:2');
        return [
            'name'             => 'required|max:30|min:2',
            'title'            => 'required|max:30|min:2',
            'body'             => 'required',
            'meta_title'       => REQUIRED_MIN_2_MAX_500,
            'meta_description' => REQUIRED_MIN_2_MAX_500,
            'meta_keywords'    => REQUIRED_MIN_2_MAX_500,
        ];
    }
}

