<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaManagerRequest extends FormRequest
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
        $model = $this->mediamanager;
        return [
            'name'=>'required','max:30',($model) ? Rule::unique('mediamanagers', 'name')->ignore($model->id) : 'unique:mediamanagers,name',
            'image.*' => ['image', 'mimes:jpeg,jpg,png', 'max:1024'],

          
        ];
    }

 
}
