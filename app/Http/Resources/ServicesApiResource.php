<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicesApiResource extends JsonResource
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
        'service_cost' => $this->service_cost,
        'service_date' => $this->created_at,
        'service_provider' => new ServiceProviderApiResource($this->whenLoaded('serviceProvider')),
        'service_type' => new ServiceTypeApiResource($this->whenLoaded('serviceType')),
        'upload_receipt' => $this->Receipts,
        'next_service_mileage' => $this->service_mileage,
        'user' => new UserApiResource($this->whenLoaded('user')),
        'vehicle_detal' => new VehicleResource($this->whenLoaded('vehicle')),
    ];
    }
}
