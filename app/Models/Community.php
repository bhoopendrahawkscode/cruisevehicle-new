<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BaseModel;
use App\Constants\Constant;
use App\Services\ImageService;
use App\Models\CommunityUser;

class Community extends BaseModel
{

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'communities';


    protected $fillable = [
        'name','image','type','question','user_id','code'
    ];

    public function getThumbImageAttribute()
    {
        if (
            ImageService::exists(Constant::COMMUNITY_FOLDER.$this->image)
            && $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Constant::COMMUNITY_FOLDER . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    public function getPeopleAttribute()
    {
        // only accepted members
        return CommunityUser::where(['community_id'=> $this->id,'status'=>2])->count();
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
          ImageService::deleteImage(Constant::COMMUNITY_FOLDER,$model->image);
        });
    }

}
