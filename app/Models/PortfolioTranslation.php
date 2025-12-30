<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "portfolio_translations";
    protected $fillable = ["portfolio_id", "language_id", 'description', 'title', 'url', 'created_at', 'updated_at'];


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
