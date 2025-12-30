<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\MultiLangActivityService;

/**
 * Cms Model
 */
class Cms extends BaseModel   {

	use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'cmss';
    protected $translationsAttributes = ['title','body','meta_title','meta_description','meta_keywords'];

    
    public function cms_translations()
    {
        return $this->hasMany('App\Models\CmsTranslation','cms_id');
    }
    public function cms_translation()
    {
        return $this->hasOne('App\Models\CmsTranslation','cms_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model:$model,hasManyTranslations:$model->cms_translations(),hasOneTranslation:$model->cms_translation());
            $model->cms_translations()->delete();
        });
  
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->cms_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model:$model);
        });

        
    }

}
