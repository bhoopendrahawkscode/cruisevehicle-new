<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelRefill extends BaseModel
{
    use SoftDeletes;
    protected $guarded = [];

    public function getThumbImageAttribute()
    {
        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.IMAGE_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function getReceiptsAttribute()
    {
        if (
            $this->upload_receipt != NULL
        ) {
            $image = url(ImageService::getImageUrl('upload_receipt/' . $this->upload_receipt));
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function user()
    {
        return $this->belongsTo(User::class,);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,'vehicle_id');
    }
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class,);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class,);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class,'fuel_type');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function CarModel()
    {
        return $this->belongsTo(CarModel::class,'model_id');
    }

    

    public function CoverType()
    {
        return $this->belongsTo(InsuranceCoverType::class,'cover_type_id');
    }


    public function engineCapacity()
    {
        return $this->belongsTo(EngineCapacity::class);
    }
    public function InsuranceCompany()
    {
        return $this->belongsTo(User::class,'insurance_company');
    }
    

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }


    /**
     * Get all of the comments for the Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

}
