<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'street' => ['required', 'string'],
            'town' => ['required', 'string'],
            'address' => ['required', 'string'],
            'country_code' => ['required', 'string'],
            'mobile_no' => [
                'required',
                'numeric',           // Ensures it's a valid number
                'digits_between:6,12' // Ensures a minimum of 6 digits and a maximum of 15 (international standard max length)
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'mobile_no.digits_between' => 'The mobile number must be at least 6 digits and no more than 12 digits.',
            'mobile_no.numeric' => 'The mobile number must be a valid number.',
        ];
    }
}
