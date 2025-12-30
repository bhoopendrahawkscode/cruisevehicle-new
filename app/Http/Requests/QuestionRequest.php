<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class QuestionRequest extends FormRequest
{

    public function attributes()
    {
        return[
            'name.*' => __('messages.question'),
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

        $id = $this->route('id');
        return  [
            'name.*' => [
                'required',
                'min:20',
                'max:500',
                new UniqueTranslationRule(['question_translations',$id]) // PGupta custom validation message
            ]
        ];


    }

}
