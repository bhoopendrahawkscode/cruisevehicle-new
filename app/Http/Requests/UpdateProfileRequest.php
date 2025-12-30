<?php
namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Constant;
class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $authId = auth()->user()->id;

        $validate = [
            /*'first_name'    => 'required|max:30|min:2',
            'last_name'     => 'required|max:30|min:2',*/
            'full_name'     => 'required|max:60|min:2',
            'email'         => ['required', 'email', 'max:100', 'min:2', Rule::unique('users', 'email')->ignore($authId)],
            /*'mobile_no'  => [
                'required',
                'numeric',
                'digits_between:6,12',
                Rule::unique('users', 'mobile_no')
                    ->where(function ($query) {
                        $query->where('id', '!=', auth()->user()->id);
                    })
            ]*/
        ];
        /*if(!Constant::ALLOW_SUB_ADMIN_PHONE_NO){
            unset($validate['mobile_no']);
        }*/
        return $validate;
    }

    public function messages()
    {
        return [
            'mobile_no.phone_unick' => 'Phone number already exists.',
            // Customize other error messages here if needed
        ];
    }
}



