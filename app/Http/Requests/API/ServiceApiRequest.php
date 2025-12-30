<?php

namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;
use App\Models\Service;

class ServiceApiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'service_date' => 'required|date',
            'service_mileage' => 'required|numeric|min:0',
            'service_provider_id' => 'required|exists:service_providers,id|integer',
            'service_type_id' => 'required|exists:service_types,id|integer',
            'vehicle_id' => 'required|exists:vehicles,id|integer',
            'service_cost' => 'required|numeric|min:0',
            'upload_receipt' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Get the current vehicle ID and user ID
            $vehicleId = $this->input('vehicle_id');
            $currentUserId = auth()->user()->id;

            // Get the last recorded service mileage for this vehicle for the current user
            $lastService = Service::where('vehicle_id', $vehicleId)
                ->where('user_id', $currentUserId)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastService) {
                // Check if the entered service mileage is greater than the last recorded mileage
                if ($this->input('service_mileage') <= $lastService->service_mileage) {
                    $validator->errors()->add('service_mileage', 'The mileage must be greater than the last recorded mileage (' . $lastService->service_mileage . ' Kmph).');
                }
            }
        });
    }
}
