<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class FaqCategoryTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "faqcategories_translations";
    protected $fillable = ["faqcategories_id", "language_id", 'name', 'created_at', 'updated_at'];
}
