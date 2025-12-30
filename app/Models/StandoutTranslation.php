<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
class StandoutTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "standout_translations";
    protected $fillable = ["standout_id", "language_id",'name','description','created_at','updated_at'];


}
