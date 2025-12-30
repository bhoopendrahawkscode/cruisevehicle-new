<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "banner_translations";
    protected $fillable = ["banner_id", "language_id", 'description', 'title', 'created_at','banner_link', 'updated_at'];


    /**
     * Get the user that owns the TestimonialTranslation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class,'language_id');
    }
}
