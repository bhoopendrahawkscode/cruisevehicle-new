<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class EmailTemplateTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "emailtemplate_translations";
    protected $fillable = ["emailtemplate_id", "language_id",'name','created_at','updated_at'];




    public function emailtemplate()
    {
        return $this->belongsTo('App\Models\EmailTemplate','emailtemplate_id');
    }


}
