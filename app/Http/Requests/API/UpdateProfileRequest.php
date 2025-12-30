<?php
namespace App\Http\Requests\API;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        // Update this if you have authorization logic
        return true;
    }

    public function rules()
    {

        return [
            'email' => [
                'nullable',
                'email',
                'min:2',
                'max:100',
                Rule::unique('users', 'email')->ignore(Auth::id())
            ],
            'country_code' => [
                'required_with:mobile_no',
                'min:2',
                'max:3'
            ],
            'mobile_no' => [
                'nullable',
                'numeric',
                'digits_between:6,15',
                Rule::unique('users')->where(function ($query) {
                    if ($this->filled('mobile_no')) {
                        return $query->where('country_code', $this->input('country_code'))
                                     ->where('mobile_no', $this->input('mobile_no'));
                    }
                })->ignore(Auth::id())
            ],
            'social' => 'nullable|boolean',
            'otp' => 'nullable|string',
            'image' => 'nullable|image',
            'full_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'timezone' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->filled('email') && !$this->filled('mobile_no')) {
                $validator->errors()->add('email', 'At least one of email or mobile number must be provided.');
                $validator->errors()->add('mobile_no', 'At least one of email or mobile number must be provided.');
            }

            if ($this->filled('email') && $this->filled('mobile_no')) {
                $validator->errors()->add('email', 'You can update either email or mobile number, not both.');
                $validator->errors()->add('mobile_no', 'You can update either email or mobile number, not both.');
            }
        });
    }
}
