<?php
namespace App\Models;
use App\Models\BaseModel;
use App\Services\CommonService;
class Category extends BaseModel   {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'categories';

    protected $fillable = ["parent_id"];


    public function category_translations()
    {
        return $this->hasMany('App\Models\CategoryTranslation','category_id');
    }

    public function category_translation()
    {
        return $this->hasOne('App\Models\CategoryTranslation','category_id')
        ->where('language_id', CommonService::getLangIdFromLocale());
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->category_translations()->delete();
        });
    }


}
