<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class ArchiveCommunityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return  [
            'community_id'  => ['required'],
            'type'  => ['required',"in:0,1"]
        ];

    }


}
