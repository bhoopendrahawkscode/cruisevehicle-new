<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Services\CommonService;
use App\Models\FaqCategoryTranslation;

class FaqCategory extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'faqcategories';

    protected $fillable = ["image"];

    const FAQCATEGORYTRANSLATION = FaqCategoryTranslation::class;

    public function faq_category_translations()
    {
        return $this->hasMany(self::FAQCATEGORYTRANSLATION, 'faqcategories_id');
    }

    public function faq_category_translation()
    {
        return $this->hasOne(self::FAQCATEGORYTRANSLATION, 'faqcategories_id')
            ->select(['faqcategories_id', 'name'])
            ->where('language_id', CommonService::getLangIdFromLocale());
    }

    public function faqs()
    {
        return $this->hasMany('App\Models\Faq', 'faq_category_id');
    }

    public function faq_category_trans()
    {
        return $this->hasOne(self::FAQCATEGORYTRANSLATION, 'faqcategories_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->faq_category_translations()->delete();
        });
    }
}
