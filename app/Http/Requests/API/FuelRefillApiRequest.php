<?php

namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;
use App\Models\FuelRefill;

class FuelRefillApiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fuel_refill_date' => 'required|date',
            'fuel_refill_mileage' => 'required|numeric|min:0',
            'fuel' => 'required|numeric|min:0',
            'vehicle_id' => 'required|exists:vehicles,id|integer',
            'fuel_refill_cost' => 'required|numeric|min:0',
            'upload_receipt' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fuelRefillId = $this->input('fuel_refill_id'); // Check if it's an update
            $vehicleId = $this->input('vehicle_id');
            $fuelRefillDate = $this->input('fuel_refill_date');
            $currentUserId = auth()->id();
            $enteredMileage = $this->input('fuel_refill_mileage');
    
            // Query to get the last fuel refill before the current entry
            $lastFuelRefill = FuelRefill::where('vehicle_id', $vehicleId)
                ->where('user_id', $currentUserId)
                ->whereDate('fuel_refill_date', '<=', $fuelRefillDate)
                ->when($fuelRefillId, function ($query) use ($fuelRefillId) {
                    return $query->where('id', '!=', $fuelRefillId); // Exclude current entry if updating
                })
                ->orderBy('fuel_refill_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
    
            if ($lastFuelRefill && $enteredMileage <= $lastFuelRefill->fuel_refill_mileage) {
                $validator->errors()->add(
                    'fuel_refill_mileage',
                    'The mileage must be greater than the last recorded mileage (' . $lastFuelRefill->fuel_refill_mileage . ') on or before ' . $fuelRefillDate . '.'
                );
            }
        });
    }


   
}
