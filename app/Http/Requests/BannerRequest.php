<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Constants\Constant;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'title.*' => __('messages.title'),
            'description.*' => __('messages.content'),
        ];
    }

    public function messages()
    {
        return $this->generateValidationMessages(['title', 'description']);
    }
    
    private function generateValidationMessages(array $fields)
    {
        $messages = [];
        define('MESSAGES','messages');
        foreach ($fields as $field) {
            $messages["$field.*.required"] = __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => __(MESSAGES . $field)]);
            $messages["$field.*.min"] = __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => __(MESSAGES . $field)]);
            $messages["$field.*.max"] = __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => __(MESSAGES . $field)]);
        }
    
        return $messages;
    }
    


    /*
        Define validation rules.
    */
    public function rules()
    {

        define("MINS", "min:2");

        $id = $this->route('id');
        $validationsBanner =    [
            'title.*' => [
                'required',
                MINS,
                'max:30'
            ],
            'description.*' => [
                'required',
                MINS
            ],

        ];
        if (!$id) {
            $validationsBanner = array_merge($validationsBanner, array('image' => 'required|image|mimes:jpeg,jpg,png,gif|max:1024'));
        } else {
            $validationsBanner = array_merge($validationsBanner, array('image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024'));
        }
        return $validationsBanner;
    }
}
