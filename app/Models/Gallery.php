<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Traits\StatusAttribute;
use App\Services\MultiLangActivityService;

class Gallery extends BaseModel
{

    use SoftDeletes, StatusAttribute;

    protected $table = 'galleries';
    protected $fillable = ['image', 'status', 'created_at', 'updated_at'];
    protected $translationsAttributes = ['title', 'description'];

    public function getThumbImageAttribute()
    {
        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.GALLERY_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function gallery_translations()
    {
        return $this->hasMany('App\Models\GalleryTranslation', 'gallery_id');
    }
    public function gallery_translation()
    {
        return $this->hasOne('App\Models\GalleryTranslation', 'gallery_id');
    }

    public function mediaManagers()
    {
        return $this->belongsToMany(MediaManager::class)->withPivot('media_manager_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model: $model, hasManyTranslations: $model->gallery_translations(), hasOneTranslation: $model->gallery_translation());
            $model->gallery_translations()->delete();
        });

        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model: $model, hasManyTranslations: $model->gallery_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model: $model);
        });
    }

    public static function deleteTo($id)
    {
        $row =  self::where('id', $id)->first();
        pr($row);
        die;
    }
}
