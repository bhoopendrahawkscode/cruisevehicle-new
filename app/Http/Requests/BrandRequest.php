<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $model = $this->brand;
        return [
            'name'=>['required','max:30',($model) ? Rule::unique('brands', 'name')->ignore($model->id) : 'unique:brands,name'],
            'image.*' => ['image', 'mimes:jpeg,jpg,png', 'max:1024'],
        ];
    }


}
