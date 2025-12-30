<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class EmailTemplateRequest extends FormRequest
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
            'name.*' => [
                'required',
                MIN2,
                'max:30',
                new UniqueTranslationRule(['emailtemplate_translations',$id]) // PGupta custom validation message
            ],
            'subject.*' => [
                'required',
                MIN2,
                'max:30',
                new UniqueTranslationRule(['emailtemplate_translations',$id]) // PGupta custom validation message
            ],
            'email_body.*' => [
                'required',
                MIN2
            ]
        ];

    }

}
