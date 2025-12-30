<?php

namespace App\Models;
use App\Models\BaseModel;
class QuestionTranslation extends BaseModel
{
    public $timestamps = true;
	protected $table = "question_translations";
    protected $fillable = ["question_id", "language_id",'name','description','created_at','updated_at'];


}
