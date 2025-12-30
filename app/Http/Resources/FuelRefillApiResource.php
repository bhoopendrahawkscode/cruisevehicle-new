<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelRefillApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fuel_refill_mileage' => $this->fuel_refill_mileage,
            'fuel' => $this->fuel,
            'fuel_refill_cost' => $this->fuel_refill_cost,
            'fuel_refill_date' => $this->fuel_refill_date,
            'efficiency_rate' => $this->efficiency_rate,
            'upload_receipt' => $this->Receipts,
            'user' => new UserApiResource($this->whenLoaded('user')),
            'fuel_type' => new FuelTypeResource($this->whenLoaded('fuelType')),
            'vehicle_detal' => new VehicleResource($this->whenLoaded('vehicle')),
        ];
    }
}
