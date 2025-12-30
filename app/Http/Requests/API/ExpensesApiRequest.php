<?php

namespace App\Http\Requests\API;
use App\Http\Requests\FormRequest;

class ExpensesApiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expense_date' => 'required|date',
            'expenses_type_id' => 'required|exists:expense_types,id|integer',
            'vehicle_id' => 'required|exists:vehicles,id|integer',
                'cost' => 'required|',
            'upload_receipt' => 'nullable|mimes:pdf|max:2048',

        ];
    }
}
