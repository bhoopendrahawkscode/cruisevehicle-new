<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class BlogReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return  [
            'blog_id'  => ['required'],
            'type'  => ['required'],
            'message'  => ['required','min:2','max:1000']
        ];

    }


}
