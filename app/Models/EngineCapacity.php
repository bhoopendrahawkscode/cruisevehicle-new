<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\SoftDeletes;

class EngineCapacity extends BaseModel
{
    use SoftDeletes;
    protected $fillable = ['name','image','status','capacity','brand_id','model_id'];

    public function getThumbImageAttribute()
    {
        if ($this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.IMAGE_FOLDER') . 'thumb_' . $this->image);
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
}
