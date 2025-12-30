<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
use Auth;
class CommunityActionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type           =   $this->input('type');

        $validate =  [
            'type'  => ['required'],
        ];
        if($type == 'JOIN' || $type == 'LEAVE'){
            $validate  = array_merge($validate,['community_id'  => ['required']]);
        }elseif($type == 'JOIN_CODE'){
            $validate  = array_merge($validate,['code'  => ['required']]);
        }
        return $validate;

    }


}
