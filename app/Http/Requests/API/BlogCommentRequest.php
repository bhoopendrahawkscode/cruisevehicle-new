<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class BlogCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return  [
            'blog_id'  => ['required'],
            'content'  => ['required'],
        ];

    }


}
