<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
use Auth;
class BlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'community_id'  => ['required'],
            'content'  => ['required',Constant::MIN_2],
            'asking_help'  => ['nullable',Constant::MIN_2,"max:500"],
            'offer_help'  => ['nullable',Constant::MIN_2,"max:500"],
        ];
    }


}
