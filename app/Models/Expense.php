<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;

class Expense extends BaseModel
{
    protected $guarded = [];

    public function getReceiptsAttribute()
    {
        if (
            $this->upload_receipt != NULL
        ) {
            $image = url(ImageService::getImageUrl($this->upload_receipt));
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
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
        return $this->belongsTo(InsuranceCoverType::class,'fuel_id');
    }


    public function engineCapacity()
    {
        return $this->belongsTo(EngineCapacity::class);
    }
    public function ExpenseType()
    {
        return $this->belongsTo(ExpenseType::class,'expenses_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }



}
