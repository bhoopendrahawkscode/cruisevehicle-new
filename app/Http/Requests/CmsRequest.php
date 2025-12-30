<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class CmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /*
        Define validation rules.
    */
    public function rules()
    {

        define("MIN2",'min:2');

        $id = $this->route('id');
        return   [
            'nameMain'=>'required',
            'name.*' => [
                'required',
                MIN2,
                'max:30',
                new UniqueTranslationRule(['cms_translations',$id])
            ],
            'title.*' => [
                'required',
                MIN2,
                'max:55',
                new UniqueTranslationRule(['cms_translations',$id])
            ],
            'meta_title.*' => [
                'required',
                MIN2,
                'max:500'
            ],
            'meta_description.*' => [
                'required',
                MIN2,
                'max:1000'
            ],
            'meta_keywords.*' => [
                'required',
                MIN2,
                'max:2000'
            ],
            'body.*' => [
                'required',
                MIN2
            ],
        ];

    }

}
