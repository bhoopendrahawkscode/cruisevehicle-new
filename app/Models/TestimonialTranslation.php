<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "testimonial_translations";
    protected $fillable = ["testimonial_id", "language_id", 'description', 'designation','giver', 'created_at', 'updated_at'];


    /**
     * Get the user that owns the TestimonialTranslation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testimonial(): BelongsTo
    {
        return  $this->belongsTo('App\Models\Testimonial', 'testimonial_id')->select('id','image');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class,'language_id');
    }
}
