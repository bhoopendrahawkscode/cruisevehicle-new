<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class CommunityIdRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return  [
            'user_id'  => ['required'],
            'community_id'  => ['required'],
            'type'  => ['required'],
        ];

    }


}
