<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Constants\Constant;

class GalleryRequest extends FormRequest
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
        return $this->generateValidationMessages('title', 'description');
    }

    private function generateValidationMessages(...$fields)
    {
        $validation = [];
        define('MSG','messages');
        foreach ($fields as $field) {
            $validation["$field.*.required"] = __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => __(MSG . $field)]);
            $validation["$field.*.min"] = __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => __(MSG . $field)]);
            $validation["$field.*.max"] = __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => __(MSG . $field)]);
        }
        return $validation;
    }

    /*
        Define validation rules.
    */
    public function rules()
    {
       return [
            'title.*' => [
                'required',
                Constant::MIN_2,
                'max:30'
            ],
            'description.*' => [
                'required',
                Constant::MIN_2,
            ],

        ];
   
    }
}
