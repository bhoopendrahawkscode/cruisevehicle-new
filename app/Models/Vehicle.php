<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;

class Vehicle extends BaseModel
{
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


    public function getRoadCertificateAttribute()
    {

        if (
            $this->road_tax_certificate != NULL
        ) {
            $image = url(ImageService::getImageUrl($this->road_tax_certificate));
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function getLastServiceAttribute()
    {

      return  Service::where('vehicle_id', $this->id)->orderBy('created_at', 'desc')->first(['service_date','service_mileage','service_cost']);

    }

    public function getLastFuelAttribute()
    {

      return  FuelRefill::where('vehicle_id', $this->id)->orderBy('created_at', 'desc')->first(['fuel_refill_date','fuel_refill_mileage','fuel_refill_cost']);

    }


    public function getFitnessAttachmentAttribute()
    {
        if (
            $this->fitness_certificate != NULL
        ) {

            $image = url(ImageService::getImageUrl($this->fitness_certificate));
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
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

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class,'fuel_id');
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
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }


}
