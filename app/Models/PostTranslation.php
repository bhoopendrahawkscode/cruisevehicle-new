<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class PostTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "post_translations";
    protected $fillable = ["post_id", "content","tags","language_id",'title','created_at','updated_at'];


}
