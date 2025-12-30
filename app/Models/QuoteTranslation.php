<?php

namespace App\Models;
use App\Models\BaseModel;

use Illuminate\Database\Eloquent\SoftDeletes;
class QuoteTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "quote_translations";
    protected $fillable = ["quote_id", "language_id",'name','written_by','created_at','updated_at'];


}
