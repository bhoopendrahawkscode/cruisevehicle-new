<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use App\Models\SubscriptionTranslation;

class Subscription extends BaseModel
{

    const SUBSCRIPTIONTRANSLATION = SubscriptionTranslation::class;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'subscriptions';
    protected $fillable = [
    ];
    public function subscription_translations()
    {
        return $this->hasMany(self::SUBSCRIPTIONTRANSLATION, 'subscription_id');
    }
    public function subscription_translation()
    {
        return $this->hasOne(self::SUBSCRIPTIONTRANSLATION, 'subscription_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->subscription_translations()->delete();
        });
    }
}
