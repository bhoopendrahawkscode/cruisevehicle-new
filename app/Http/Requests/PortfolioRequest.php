<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Constants\Constant;

class PortfolioRequest extends FormRequest
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
        return [
            'title.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'title.*.min' => __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'title.*.max' => __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'url.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'description.*.required' => __(Constant::VALIDATION_REQUIRED, [Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
            'description.*.min' => __(Constant::VALIDATION_MIN, [Constant::MIN_FIELD => Constant::MIN, Constant::ATTRIBUTE_FIELD => Constant::ATTRIBUTE_FIELD]),
        ];
    }


    /*
        Define validation rules.
    */
    public function rules()
    {
        $id = $this->route('id');
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
            'image' => [(!$id)?null:'required', 'mimes:jpeg,jpg,png', 'max:1024']


        ];
       
    }
}
