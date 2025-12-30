<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class AudioRequest extends FormRequest
{

    public function attributes()
    {
        return[
            'name.*' => __('messages.name'),
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
                new UniqueTranslationRule(['video_translations',$id]) // PGupta custom validation message
            ],
            'artist.*' => [
                'required',
                'min:2',
                'max:30',
            ]
        ];
        if(!$id){
            $validations = array_merge($validations,array('image' => 'required|image|mimes:jpeg,jpg,png|max:1024'));
        }else{
            $validations = array_merge($validations,array('image' => 'sometimes|nullable|image|mimes:jpeg,jpg,png|max:1024'));
        }
        return $validations;

    }

}
