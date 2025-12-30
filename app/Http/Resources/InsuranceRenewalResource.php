<?php
namespace App\Http\Resources;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ImageService;
class InsuranceRenewalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vehicle = Vehicle::find($this->insuranceRenewal->vehicle_id);
        return [
            'id'                        => $this->id,
            'insurance_renewal_id'      => $this->insurance_renewal_id,
            'vehicle_id'                => $this->insuranceRenewal->vehicle_id ?? null,
            'company_id'                => $this->company_id,
            'company'                   => $this->company->full_name ?? '',
            'car_model'                 => $this->insuranceRenewal->carModel->name ?? '',
            'year_of_manufacturer'      => $this->insuranceRenewal->year_of_manufacturer ?? '',
            'vehicle_registration_mark' => $this->insuranceRenewal->vehicle_registration_mark ?? '',
            'value'                     =>  number_format( $this->insuranceRenewal->value, 2) ?? '',
            'sum_to_be_insured'         => number_format( $this->insuranceRenewal->sum_to_be_insured, 2) ?? '',
            'cover_type'                => $this->insuranceRenewal->coverType->name ?? '',
            'period_of_insurance_cover' => $this->insuranceRenewal->periodInsuranceCover->name ?? '',
            'nic'                       => $this->insuranceRenewal->nic ?? '',
            'insurance_certificate' =>  url(ImageService::getImageUrl($vehicle->insurance_certificate)) ?? '',
            'status'                    => $this->status,
            'premium_payable'           => ($this->status == 1 || $this->status == 3 || $this->status == 4) ? number_format( $this->premium_payable, 2) : null,
            'comment'                   => $this->comment ?? '',


            // 'vehicle_registered' => $this->insuranceRenewal->vehicle_registered ?? '',
            // 'vehicle_line' => $this->insuranceRenewal->vehicle_line ?? '',
            // 'vehicle_disqualified' => $this->insuranceRenewal->vehicle_disqualified ?? '',
            // 'vehicle_experience' => $this->insuranceRenewal->vehicle_experience ?? '',
            // 'vehicle_accidents' => $this->insuranceRenewal->vehicle_accidents ?? '',
            // 'vehicle_not_use' => $this->insuranceRenewal->vehicle_not_use ?? '',
            // 'vehicle_drive_illness' => $this->insuranceRenewal->vehicle_drive_illness ?? '',
            // 'vehicle_insurer' => $this->insuranceRenewal->vehicle_insurer ?? '',
            // 'full_name' => $this->insuranceRenewal->full_name ?? '',
                

        ];
    }
}
