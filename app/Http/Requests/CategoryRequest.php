<?php

namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRuleCategory;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /*
        Define validation rules.
    */
    public function rules()
    {

        $id = $this->route('id');
        return   [
            'name.*' => [
                'required',
                'min:2',
                'max:30',
                new UniqueTranslationRuleCategory(['category_translations',$id,$this->get('parent_id')])
                // parent_id is passed as third argument for category manager and faq manager
            ]
        ];


    }
    protected function withValidator($validator)
    {

        $id = $this->route('id');
        $parent_id  = $this->get('parent_id');
        $validator->after(function ($validator) use($id,$parent_id) { //PGupta
            if ($id && $id == $parent_id) {
                $validator->errors()->add('parent_id', __('validations.youCanNotParentOfYourSelf'));
            }
        });
    }

}
