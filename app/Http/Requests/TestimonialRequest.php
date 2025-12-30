<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Constants\Constant;

class TestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [

            'description.*' => __('messages.content'),
            'designation.*' => __('messages.designation'),
            'giver.*' => __('messages.giver'),

        ];
    }

    public function messages()
    {


        return [
            'description.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'description.*.min' => __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'designation.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'designation.*.min' => __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'designation.*.max' => __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'giver.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'giver.*.min' => __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'giver.*.max' => __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
        ];
    }


    /*
        Define validation rules.
    */
    public function rules()
    {

        define("MINS", "min:2");

        $id = $this->route('id');
        $validations =    [
            'description.*' => [
                'required',
                MINS
            ],
            'designation.*' => [
                'required',
                MINS,
                'max:30'
            ],
            'giver.*' => [
                'required',
                MINS,
                'max:30'
            ]

        ];
        if (!$id) {
            $validations = array_merge($validations, array('image' => 'required|image|mimes:jpeg,jpg,png|max:1024'));
        } else {
            $validations = array_merge($validations, array('image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024'));
        }
        return $validations;
    }
}
