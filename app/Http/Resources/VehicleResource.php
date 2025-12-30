<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $brandName = optional($this->brand)->name;
        $modelName = optional($this->CarModel)->name;
        $modelId = optional($this->CarModel)->id;
        $transmissionTypeName = optional($this->transmissionType)->name;

        return [
            'id' => $this->id,
            'vehicle_name' => trim("{$brandName}  {$transmissionTypeName}"),
            'owner_name' => $this->owner_name,
            'owner_address' => $this->owner_address,
            'street' => $this->street,
            'town' => $this->town,
            'reg_no' => $this->reg_no,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'model' => new CarModelResource($this->whenLoaded('CarModel')),
            'engine_capacity' => new EngineCapicityResource($this->whenLoaded('engineCapacity')),
            'fuel_type' => new FuelTypeResource($this->whenLoaded('fuelType')),
            'transmission_type' => new TransmissionTypeResource($this->whenLoaded('transmissionType')),
            'road_tax_renewal_period' => $this->renewal_period,
            'RoadCertificate' => $this->RoadCertificate,
            'LastService' => $this->LastService,
            'LastFuel' => $this->LastFuel,
            'model_id' => $modelId,
            'road_tax_expiry_date' => $this->due_renewal_date,
            'insurance_company' => new InsuranceCompanyResource($this->whenLoaded('InsuranceCompany')),
            'sum_assured_value' =>number_format( $this->sum_assured_value, 2),
            'cover_type' => new CoverTypeResource($this->whenLoaded('CoverType')),
            'premium' => number_format( $this->premium, 2),
            'chassis_number' => $this->chassis_number,
            'insurance_expiry_date' => $this->insurance_expiry_date,
            'FitnessCertificate' => $this->FitnessAttachment,
            'fitness_expiry_date' => $this->fitness_expiry_date,
            'mts_registered_date' => $this->mts_registered_date,
            'user' => new UserApiResource($this->whenLoaded('user')),

        ];
    }
}
