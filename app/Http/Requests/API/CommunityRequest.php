<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
use Auth;
class CommunityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id             =   $this->input('id');
        $name           =   $this->input('name');
        $type           =   $this->input('type');
        if($id == ''){
            $validate =  [
                'name'  => ['required',Constant::MIN_2, 'max:60',
                    Rule::unique('communities')->where(function ($query) use($name) {
                        return $query->where('name', $name);
                    })
                ],
                'image'  => ['required'],
                'type'  => ['required']
            ];
        }else{
            $validate =  [
                'name'  => ['required',Constant::MIN_2, 'max:60',
                    Rule::unique('communities')->where(function ($query) use($name,$id) {
                        return $query->where('name', $name)->where('id',"!=",$id);
                    })
                ],
                'type'  => ['required']
            ];
        }
        if($type == 2){ // if private
            $validate  = array_merge($validate,['question'  => ['required']]);
        }
        return $validate;

    }


}
