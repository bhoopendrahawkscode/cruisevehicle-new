<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\UniqueTranslationRule;
class DropdownRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');
         $getSegment = request()->segment(3);
         if($getSegment=='save'){
            $getSegment = request()->segment(4);
         }else{
            $getSegment = request()->segment(5);
         }
        $validations =   [
            'name.*' => [
                'required',
                'min:2',
                'max:30',
                new UniqueTranslationRule([$getSegment.'_translations',$id])
                //$id ? 'unique:'.$getSegment.',name,' . $id : 'unique:'.$getSegment,
            ]
        ];
        if(!$id){
            $validations = array_merge($validations,array('image' => 'sometimes|required|image|mimes:jpeg,jpg,png|max:1024'));
        }else{
            $validations = array_merge($validations,array('image' => 'sometimes|nullable|image|mimes:jpeg,jpg,png|max:1024'));
        }
        return $validations;

    }

}
