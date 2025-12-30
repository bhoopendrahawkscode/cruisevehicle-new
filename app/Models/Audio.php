<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Models\AudioTranslation;
use App\Services\MultiLangActivityService;
use App\Traits\StatusAttribute;
class Audio extends BaseModel
{

    const AUDIOTRANSLATION = AudioTranslation::class;
    use SoftDeletes,StatusAttribute;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'audios';
    protected $fillable = [
        'duration','audio','image','category','status'
    ];
    protected $translationsAttributes = ['name','artist'];


    public function audio_translations()
    {
        return $this->hasMany(self::AUDIOTRANSLATION, 'audio_id');
    }
    public function audio_translation()
    {
        return $this->hasOne(self::AUDIOTRANSLATION, 'audio_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model:$model,hasManyTranslations:$model->audio_translations(),hasOneTranslation:$model->audio_translation());

            $model->audio_translations()->delete();
        });
        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });
        
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->audio_translations());
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
            $image = ImageService::getImageUrl(Config::get('constants.AUDIO_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

}
