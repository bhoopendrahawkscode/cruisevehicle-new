<?php
namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class TemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
       $id = $this->route('id');

        $rules = [
            'name'       => 'required|max:30|min:2',
            'subject'    => 'required|max:30|min:2',
            'email_body' => 'required',
        ];
        if ($id=='') {
            $rules += [
                'email_type' => 'required',
                'constants'  => 'required',
            ];
        }

        return $rules;
    }
}


