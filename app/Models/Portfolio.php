<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Traits\StatusAttribute;
use Illuminate\Support\Facades\Request;
use App\Services\MultiLangActivityService;

class Portfolio extends BaseModel
{

    use SoftDeletes, StatusAttribute;

    protected $table = 'portfolios';
    protected $fillable = ['image', 'status', 'created_at', 'updated_at'];
    protected $translationsAttributes = ['title', 'description'];

    public function getThumbImageAttribute()
    {

        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.PORTFOLIO_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function portfolio_translations()
    {
        return $this->hasMany('App\Models\PortfolioTranslation', 'portfolio_id');
    }
    public function portfolio_translation()
    {
        return $this->hasOne('App\Models\PortfolioTranslation', 'portfolio_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            MultiLangActivityService::DeleteActivity(model: $model, hasManyTranslations: $model->portfolio_translations(), hasOneTranslation: $model->portfolio_translation());

            $model->portfolio_translations()->delete();
        });

        static::updating(function ($model) {
            MultiLangActivityService::CreateStatusLog(model: $model);
        });
        static::updated(function ($model) {
            MultiLangActivityService::UpdateLogActivity(model: $model, hasManyTranslations: $model->portfolio_translations());
        });

        static::creating(function ($model) {
            MultiLangActivityService::CreateLogActivity(model: $model);
        });
    }


    public static function deleteTo($id)
    {
        $row =  self::where('id', $id)->first();
        pr($row);
        die;
    }
}
