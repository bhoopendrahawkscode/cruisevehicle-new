<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "gallery_translations";
    protected $fillable = ["gallery_id", "language_id", 'description', 'title', 'created_at', 'updated_at'];


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
