<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Traits\StatusAttribute;
use App\Services\MultiLangActivityService;

class Testimonial extends BaseModel
{

    use SoftDeletes, StatusAttribute;

    protected $table = 'testimonials';
    protected $fillable = ['image', 'status', 'created_at', 'updated_at'];
    protected $translationsAttributes = ['giver','description','designation'];

    public function getThumbImageAttribute()
    {

        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.TESTIMONIALS_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function testimonial_translations()
    {
        return $this->hasMany('App\Models\TestimonialTranslation', 'testimonial_id');
    }
    public function testimonial_translation()
    {
        return $this->hasOne('App\Models\TestimonialTranslation', 'testimonial_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model:$model,hasManyTranslations:$model->testimonial_translations(),hasOneTranslation:$model->testimonial_translation());
            $model->testimonial_translations()->delete();
        });
        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });

        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->testimonial_translation());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model:$model);
        });


    }


    public static function deleteTo($id)
    {
        $row =  self::where('id', $id)->first();
        pr($row);
        die;
    }
}
