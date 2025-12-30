<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Services\CommonService;
use App\Models\FaqTranslation;

class Faq extends BaseModel
{
    const FAQTRANSLATION = FaqTranslation::class;

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'faqs';
    protected $fillable = ['faq_category_id', 'status', 'created_at', 'updated_at'];
    public function getThumbImageAttribute()
    {

        if (
            ImageService::exists(Config::get('constants.FAQ_FOLDER') . $this->image)
            && $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.FAQ_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function faq_translations()
    {
        return $this->hasMany(self::FAQTRANSLATION, 'faq_id');
    }
    public function faq_translation()
    {
        return $this->hasOne(self::FAQTRANSLATION, 'category_id')
            ->where('language_id', CommonService::getLangIdFromLocale());
    }

    public function faq_category()
    {
        return $this->belongsTo('App\Models\FaqCategory', 'faq_category_id')->with('faq_category_translation');
    }

    public function faq_trans()
    {
        return $this->hasOne(self::FAQTRANSLATION, 'faq_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->faq_translations()->delete();
        });
    }
}
