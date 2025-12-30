<?php

namespace App\Http\Requests;

use App\Constants\Constant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $meta = $this->meta;
        return [
            'meta_title.*' => Constant::MAX_30,
            'meta_key.*' => Constant::MAX_30,
            'image.*' => ['image', 'mimes:jpeg,jpg,png', 'max:1024'],

            'meta_description.*' => 'max:160',
            'name' => 'required', Constant::MAX_30, ($meta) ? Rule::unique('coupons', 'name')->ignore($meta->id) : 'unique:metas,name',
            'url' => ($meta) ? '' : 'required', 'url', ($meta) ? Rule::unique('coupons', 'url')->ignore($meta->id) : 'unique:metas,url',
        ];
    }

    public function messages()
    {
        return [
            'image.*.max' => __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => __('messages.image')]),
            'image.*.mimes' => __(Constant::VALIDATION_MIMES, [Constant::ATTRIBUTE_FIELD => __('messages.image'),'values'=>'jpeg,jpg,png']),
            'meta_description.*.max' => __(Constant::VALIDATION_MAX, [Constant::MAX_FIELD => Constant::MAX, Constant::ATTRIBUTE_FIELD => __('messages.meta_description')])
          ];

    }
}
