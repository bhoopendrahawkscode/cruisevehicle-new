<?php
namespace App\Http\Requests\API;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;

class VehicleApiRequest extends FormRequest
{
    public function authorize()
    {
        $user = Auth::user();
        $vehicleCount = Vehicle::where('user_id', $user->id)->count();

        if ($this->isMethod('post') && $vehicleCount >= 3 && $this->input('vehicle_id')=='') {
            return false;
        }

        return true;
    }

    public function rules()
    {
        $vehicleId = $this->route('vehicle') ?? $this->input('vehicle_id');
        
        return [
            'vehicle_id' => 'nullable|exists:vehicles,id|integer',
            'owner_name' => 'required|string|max:255',
            'owner_address' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'reg_no' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($vehicleId) {
                    $user = Auth::user();
                    // Check if the vehicle with the same reg_no exists for the user, excluding the current vehicle in updates
                    $exists = Vehicle::where('user_id', $user->id)
                        ->where('reg_no', $value)
                        ->when($vehicleId, function ($query) use ($vehicleId) {
                            return $query->where('id', '!=', $vehicleId);
                        })
                        ->exists();
                    
                    if ($exists) {
                        $fail('This vehicle is already added in the system.');
                    }
                },
            ],
            'brand_id' => 'required|exists:brands,id|integer',
            'model_id' => 'required|exists:models,id|integer',
            'engine_capacity_id' => 'required|exists:engine_capacities,id|integer',
            'fuel_id' => 'required|exists:fuel_types,id|integer',
            'transmission_type_id' => 'required|exists:transmission_types,id|integer',
            'renewal_period' => 'required|string|max:255',
            'due_renewal_date' => 'required|date',
            'road_tax_certificate' => $vehicleId ? 'nullable|mimes:pdf|max:2048' : 'required|mimes:pdf|max:2048',
            'fitness_certificate' => $vehicleId ? 'nullable|mimes:pdf|max:2048' : 'required|mimes:pdf|max:2048',
            'fitness_expiry_date' => 'required|date',
        ];
    }

    protected function failedAuthorization()
    {
        abort(response()->json([
            'status' => 403,
            'message' => 'You cannot add more than 3 vehicles.'
        ], 403));
    }
}
