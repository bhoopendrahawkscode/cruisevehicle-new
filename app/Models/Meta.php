<?php

namespace App\Models;

use App\Constants\Constant;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MetaTranslation;
use App\Services\ImageService;
use App\Services\MultiLangActivityService;
use Illuminate\Support\Facades\Config;

class Meta extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'url'];
    protected $translationsAttributes = ['meta_title', 'meta_description',  'meta_key'];


    public function meta_translations()
    {
        return $this->hasMany(MetaTranslation::class, 'meta_id');
    }
    public function meta_translation()
    {
        return $this->hasOne(MetaTranslation::class, 'meta_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->meta_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model:$model);
        });

        
    }
    

    public function getThumbImageAttribute()
    {
        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.META_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function updateManyTranslations(array $translations,object $meta)
    {

        foreach ($translations as $translation) {
            $translationId = $translation['id'];
            unset($translation['id']);
            $this->meta_translations()->where('id', $translationId)->update($translation);
        }
    }


}


