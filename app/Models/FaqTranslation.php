<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
class FaqTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "faq_translations";
    protected $fillable = ["faq_id", "language_id", 'question', 'answer', 'created_at', 'updated_at'];
}
