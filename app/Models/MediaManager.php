<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Services\ImageService;
class MediaManager extends BaseModel
{
    protected $table ='media_managers';
    protected $fillable =[ 'name','image'];

    public function getThumbImageAttribute()
    {
        if ($this->image != null
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.MEDIA_FOLDER') . '/thumbnail/' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class);
    }
}
