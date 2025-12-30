<?php
namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;

class TrackMoodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_id'    => 'required',
            'score'     => 'required|numeric|between:1,5'
        ];
    }


}



