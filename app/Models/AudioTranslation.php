<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class AudioTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "audio_translations";
    protected $fillable = ["audio_id", "language_id",'name','artist','created_at','updated_at'];


}
