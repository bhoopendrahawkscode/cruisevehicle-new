<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class PostRequest extends FormRequest
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
        $validations =    [
            'title.*' => [
                'required',
                'min:2',
                'max:500',
                new UniqueTranslationRule(['post_translations',$id]) // PGupta custom validation message
            ]
        ];
        if(!$id){
            $validations = array_merge($validations,array('image' => 'required|image|mimes:jpeg,jpg,png|max:1024'));
        }else{
            $validations = array_merge($validations,array('image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024'));
        }
        return $validations;

    }

}
