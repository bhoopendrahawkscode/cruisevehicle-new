<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Models\BaseModel;
use App\Traits\StatusAttribute;
use App\Services\MultiLangActivityService;
use Illuminate\Support\Facades\Config;

class Banner extends BaseModel
{

    use SoftDeletes, StatusAttribute;

    protected $table = 'banners';
    protected $fillable = ['image', 'status', 'created_at', 'updated_at'];
    protected $translationsAttributes = ['title', 'description'];

    public function getThumbImageAttribute()
    {

        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.BANNER_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    
    public function getBannerImageAttribute()
    {
        if (
            $this->image != NULL
        ) {
            $image = url(ImageService::getImageUrl('uploads/banner/' . $this->image));
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function banner_translations()
    {
        return $this->hasMany('App\Models\BannerTranslation', 'banner_id');
    }
    public function banner_translation()
    {
        return $this->hasOne('App\Models\BannerTranslation', 'banner_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model: $model, hasManyTranslations: $model->banner_translations(), hasOneTranslation: $model->banner_translation());

            $model->banner_translations()->delete();
        });

    
        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model: $model, hasManyTranslations: $model->banner_translations());
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
