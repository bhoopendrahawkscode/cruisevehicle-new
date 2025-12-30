<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesApiResource extends JsonResource
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
            'cost' => number_format( $this->cost, 2),
            'date' => $this->expense_date,
            'fuel' => $this->fuel,
            'mileage' => $this->mileage,
            'expenses_type' => new ExpensesTypeApiResource($this->whenLoaded('ExpenseType')),
            'upload_receipt' => $this->Receipts,
            'user' => new UserApiResource($this->whenLoaded('user')),
            'vehicle_detal' => new VehicleResource($this->whenLoaded('vehicle')),
        ];
    }
}
