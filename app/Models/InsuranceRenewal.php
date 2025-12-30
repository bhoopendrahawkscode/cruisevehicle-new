<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRenewal extends Model
{
    protected $table = 'insurance_renewal';
    protected $primaryKey = 'id';


    protected $fillable = [
        'vehicle_registered',
        'vehicle_id',
        'vehicle_line',
        'vehicle_disqualified',
        'vehicle_experience',
        'vehicle_accidents',
        'vehicle_not_use',
        'vehicle_drive_illness',
        'vehicle_insurer',
        'full_name',
        'nic',
        'car_model',
        'year_of_manufacturer',
        'vehicle_registration_mark',
        'value',
        'sum_to_be_insured',
        'cover_type',
        'period_of_insurance_cover',
        'status',
    ];


    public function carModel()
    {
        return $this->belongsTo(CarModel::class,'car_model');
    }

    public function coverType()
    {
        return $this->belongsTo(InsuranceCoverType::class,'cover_type');
    }

    public function periodInsuranceCover()
    {
        return $this->belongsTo(InsuranceCoverPeriod::class,'period_of_insurance_cover');
    }

    public function CompanyInsuranceRenewal()
    {
        return $this->hasMany('App\Models\CompanyInsuranceRenewal', 'insurance_renewal_id', 'id');
    }

}
