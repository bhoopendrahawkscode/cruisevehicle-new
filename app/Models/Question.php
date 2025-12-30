<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Models\QuestionTranslation;

class Question extends BaseModel
{

    const QUESTION_TRANSLATION = QuestionTranslation::class;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'questions';
    protected $fillable = [
    ];
    public function question_translations()
    {
        return $this->hasMany(self::QUESTION_TRANSLATION, 'question_id');
    }
    public function question_translation()
    {
        return $this->hasOne(self::QUESTION_TRANSLATION, 'question_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->question_translations()->delete();
        });
    }
}
