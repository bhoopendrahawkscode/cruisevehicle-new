<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransmissionType extends BaseModel   
{
    use SoftDeletes;
    protected $fillable =[ 'name','image','status' ];

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
}
