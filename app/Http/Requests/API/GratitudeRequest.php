<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
use Auth;
class GratitudeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id             =   $this->input('id');
        $title          =   $this->input('title');
        $userId         =   Auth::user()->id;
        if($id == ''){
            return [
                'title'  => ['required',Constant::MIN_2, 'max:60',
                    Rule::unique('gratitudes')->where(function ($query) use($userId,$title) {
                        return $query->where('user_id', $userId)->where('title', $title);
                    })
                ],
                'description'  => ['required',Constant::MIN_2, 'max:1000']
            ];
        }else{
            return [
                'title'  => ['required',Constant::MIN_2, 'max:60',
                    Rule::unique('gratitudes')->where(function ($query) use($userId,$title,$id) {
                        return $query->where('user_id', $userId)->where('title', $title)->where('id',"!=",$id);
                    })
                ],
                'description'  => ['required',Constant::MIN_2, 'max:1000']
            ];
        }

    }


}
