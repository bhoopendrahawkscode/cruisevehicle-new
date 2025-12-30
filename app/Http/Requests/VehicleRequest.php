<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
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
        $model = $this->vehicle;
        return [
            'owner_name'=>['required','max:30'],
            'owner_address'=>['required','max:300'],
            'reg_no'=>['required','min:2',($model) ? Rule::unique('vehicles','reg_no')->ignore($model->id) : 'unique:vehicles,reg_no'],
            'image.*' => ['image', 'mimes:jpeg,jpg,png', 'max:1024'],


        ];
    }


}
