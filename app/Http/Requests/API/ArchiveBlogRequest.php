<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class ArchiveBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return  [
            'blog_id'  => ['required'],
            'type'  => ['required',"in:1,2,3,4"]
        ];

    }


}
