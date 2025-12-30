<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class QuoteRequest extends FormRequest
{

    public function attributes()
    {
        return[
            'name.*' => __('messages.quote'),
            'written_by.*' => __('messages.written_by'),
        ];
    }

    public function authorize()
    {
        return true;
    }

    /*
        Define validation rules.
    */
    public function rules()
    {

        return   [
            'name.*' => [
                'required',
                'min:20',
                'max:150',
            ],
            'written_by.*' => [
                'required',
                'min:20',
                'max:50'
            ]
        ];

    }

}
