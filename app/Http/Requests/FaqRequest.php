<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class FaqRequest extends FormRequest
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

        $id = $this->route('id');
        return   [
            'question.*' => [
                'required',
                'min:2',
                new UniqueTranslationRule(['faq_translations', $id]) // PGupta custom validation message
            ],
            'answer.*' => [
                'required',
                'min:2',
            ],
        ];
    }
}
