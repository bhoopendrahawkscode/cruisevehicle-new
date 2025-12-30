<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\CommonService;
use App\Services\MultiLangActivityService;
class EmailTemplate extends BaseModel   {

	use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'emailtemplates';
    protected $translationsAttributes = ['name','subject','email_body'];


    public function emailtemplate_translations()
    {
        return $this->hasMany('App\Models\EmailTemplateTranslation','emailtemplate_id');
    }
    public function emailtemplate_translation()
    {
        return $this->hasOne('App\Models\EmailTemplateTranslation','emailtemplate_id')->where('language_id', CommonService::getLangIdFromLocale());
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model:$model,hasManyTranslations:$model->emailtemplate_translations(),hasOneTranslation:$model->emailtemplate_translation());
            $model->emailtemplate_translations()->delete();
        });
          
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model:$model,hasManyTranslations:$model->emailtemplate_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model:$model);
        });
        
    }

}
