<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;

class SubscriptionRequest extends FormRequest
{

    public function attributes()
    {
        return[
            'name.*' => __('messages.subscription'),
            'validity.*' => __('messages.validity'),
            'price.*' => __('messages.price'),
            'video_price.*' => __('messages.price'),
            'songs_service.*' => __('messages.songs_service'),
            'video_service.*' => __('messages.video_service'),
            'subscription_type.*' => __('messages.subscription_type'),

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

    define('MIN_LENGTH', 'min:1');
    define('MAX_50', 'max:50');
    define('BETWEEN', 'between:1,100000');

        $id = $this->route('id');
        $rules=   [
            'name.*' => [
                'required',
                'min:2',
                'max:30',
                new UniqueTranslationRule(['subscription_translations',$id]) // PGupta custom validation message
            ],
            'validity.*' => [
                'required',
                MIN_LENGTH,
                'integer',
                'between:1,31',

            ],
            'songs_service.*' => [
                'required',
                MIN_LENGTH,
                MAX_50
            ],
            'video_service.*' => [
                'required',
                MIN_LENGTH,
                MAX_50
            ],
            'subscription_type.*' => [
                'required',
                MIN_LENGTH,
                MAX_50
            ]

        ];
        if ($this->has('price')) {
            $rules['price.*'] = [
                'required',
                'numeric',
                BETWEEN
            ];
        }

        if ($this->has('video_price')) {
            $rules['video_price.*'] = [
                'required',
                'numeric',
                BETWEEN
            ];
        }

        return  $rules;
    }

}
