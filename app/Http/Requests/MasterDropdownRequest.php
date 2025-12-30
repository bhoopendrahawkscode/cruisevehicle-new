<?php
namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;


class MasterDropdownRequest extends FormRequest
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

        return [
            'type' => [
                'required',
                'min:2',
                'max:30',
            ],
            'name' => [
                'required',
                'min:2',
                'max:30',
                Rule::unique('masterdropdowns')->where(function ($query) {
                    return $query->where('type', $this->type);
                })->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
        ];
    }

}
