<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class SubscriptionTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "subscription_translations";
    protected $fillable = ["subscription_id", "language_id",'name','validity','price','video_price','songs_type','songs_service','video_service','subscription_type','created_at','updated_at'];


}
