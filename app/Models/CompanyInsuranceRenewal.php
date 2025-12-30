<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInsuranceRenewal extends Model
{
    protected $table = 'company_insurance_renewals';

    protected $fillable = [
        'company_id',
        'insurance_renewal_id',
        'user_id',
        'attachment',
        
    ];

    // Relationships (optional, if needed)
    public function company()
    {
        return $this->belongsTo(User::class,'company_id');
    }

    public function getImageAttribute()
    {
        
        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.USER_FOLDER'). $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function insuranceRenewal()
    {
        return $this->belongsTo(InsuranceRenewal::class);
    }

   

}
