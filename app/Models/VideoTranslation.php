<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class VideoTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "video_translations";
    protected $fillable = ["video_id", "language_id",'name','artist','created_at','updated_at'];
}
