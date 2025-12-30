<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BaseModel;
use App\Models\VideoTranslation;
use App\Services\MultiLangActivityService;
use App\Traits\StatusAttribute;
class Video extends BaseModel
{

    const VIDEO_TRANSLATION = VideoTranslation::class;
    use SoftDeletes,StatusAttribute;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'videos';
    protected $fillable = [
        'duration','video','category','status'
    ];
    protected $translationsAttributes = ['name','artist'];

    public function video_translations()
    {
        return $this->hasMany(self::VIDEO_TRANSLATION, 'video_id');
    }
    public function video_translation()
    {
        return $this->hasOne(self::VIDEO_TRANSLATION, 'video_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model:$model,hasManyTranslations:$model->video_translations(),hasOneTranslation:$model->video_translation());

            $model->video_translations()->delete();
        });
        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });
        
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->video_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model:$model);
        });
    }




}
