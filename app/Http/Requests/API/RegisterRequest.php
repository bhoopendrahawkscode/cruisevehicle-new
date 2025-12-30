<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $countryCode = $this->input('country_code');
        $mobileNo = $this->input('mobile_no');

        $validations =  [
            'full_name'  => ['required',Constant::MIN_2, 'max:60',
                'regex:/^[a-zA-Z\s]*$/'
            ],
            'email'  => ['required', 'email', Constant::MIN_2, 'max:100', Rule::unique('users', 'email'),'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/i'],
            'country_code' => 'required|min:2|max:3',
            'mobile_no'  => [
                'required',
                'numeric',
                'digits_between:6,15',
                Rule::unique('users')->where(function ($query) use ($countryCode,$mobileNo) {
                    return $query->where('country_code', $countryCode)->where('mobile_no', $mobileNo);
                })
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[\d]/',
                'regex:/[@#$%^&*]/',
            ],
            'confirm_password' => 'required|same:password'
        ];

        if ($this->has('otp')) {
            $validations = array_merge($validations,['otp' => 'required']);
        }

        return $validations;
    }


}
