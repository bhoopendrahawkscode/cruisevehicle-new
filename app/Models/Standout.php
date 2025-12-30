<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Models\StandoutTranslation;

class Standout extends BaseModel
{

    const STANDOUT_TRANSLATION = StandoutTranslation::class;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'standouts';
    protected $fillable = [
        'image',
    ];
    public function getThumbImageAttribute()
    {

        if (
            ImageService::exists(Config::get('constants.STANDOUT_FOLDER') . $this->image)
            && $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.STANDOUT_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function standout_translations()
    {
        return $this->hasMany(self::STANDOUT_TRANSLATION, 'standout_id');
    }
    public function standout_translation()
    {
        return $this->hasOne(self::STANDOUT_TRANSLATION, 'standout_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->standout_translations()->delete();
        });
    }
}
