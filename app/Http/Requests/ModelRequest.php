<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModelRequest extends FormRequest
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
        $model = $this->model;
        return [
            'brand_id' => ['required', 'exists:brands,id'],
            'name' => ['required', 'max:30', ($model) ? Rule::unique('models', 'name')->ignore($model->id)->where('brand_id', $this->brand_id) :
                Rule::unique('models', 'name')->ignore('id')->where('brand_id', $this->brand_id)],
            'image.*' => ['image', 'mimes:jpeg,jpg,png', 'max:1024'],


        ];
    }
}
