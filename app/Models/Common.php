<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use Illuminate\Support\Facades\Storage;
/**
 * Common Model
 */
class Common extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'image', // Add your image column here
    ];
    public function getThumbImageAttribute()
    {

        if (ImageService::exists(Config::get('constants.DROPDOWN_FOLDER').$this->table.'/'. $this->image)
            && $this->image != null
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.DROPDOWN_FOLDER').$this->table.'/thumb_'. $this->image);
        } else {
            $image = url(Constant::NO_IMAGE);
        }

        return $image;

    }

    public function dropdown_translations()
    {
        return $this->hasMany('App\Models\CommonTranslation',"{$this->getTable()}_id", 'id');
    }
    public function dropdown_translation()
    {
        return $this->hasOne('App\Models\CommonTranslation');
    }



    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->dropdown_translations()->delete();
        });
    }
}
