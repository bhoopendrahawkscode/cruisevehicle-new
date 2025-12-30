<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
class VerifyMobileOtpCheck extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId         =   Auth::guard('admin')->user()->id;
        $countryCode = $this->input('country_code');
        $mobileNo = $this->input('mobile_no');
        return [
            'country_code' => 'required|min:2|max:3',
            'mobile_no'  => [
                'required',
                'numeric',
                'digits_between:6,15',
                Rule::unique('users')->where(function ($query) use ($countryCode,$mobileNo,$userId) {
                    return $query->where('country_code', $countryCode)->where('mobile_no', $mobileNo)->where('id', "!=", $userId);
                })
            ],
            'otp' => 'required|min:6|max:6',
        ];
    }


}
