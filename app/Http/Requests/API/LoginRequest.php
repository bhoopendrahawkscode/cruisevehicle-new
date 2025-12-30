<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->input('loginType') == ""){
            return [
                'loginType' => 'required',
            ];
        }
        if($this->input('loginType') == 'SOCIAL'){
            return [
                'social_type' => 'required|in:apple_id,facebook_id,google_id,snapchat_id,tiktok_id',
                'social_id' => 'required',
                'loginType' => 'required',
            ];
        }
        return [
            'email' => 'required',
            'password' => 'required',
            'loginType' => 'required',
        ];

    }


}
