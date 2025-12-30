<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class StandoutRequest extends FormRequest
{

    public function attributes()
    {
        return[
            'name.*' => __('messages.headline'),
            'description.*' => __('messages.description'),
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
        $validations =    [
            'name.*' => [
                'required',
                'min:20',
                'max:100',
                new UniqueTranslationRule(['standout_translations',$id]) // PGupta custom validation message
            ],
            'description.*' => [
                'required'
            ]
        ];
        $validations = array_merge($validations,array('image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024'));
        return $validations;

    }

}
