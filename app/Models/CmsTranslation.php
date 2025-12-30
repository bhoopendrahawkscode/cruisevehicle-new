<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class CmsTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "cms_translations";
    protected $fillable = ["cms_id", "language_id",'name','created_at','updated_at'];


}
