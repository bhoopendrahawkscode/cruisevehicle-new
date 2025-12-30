<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use App\Models\QuoteTranslation;

class Quote extends BaseModel
{

    const QUOTE_TRANSLATION = QuoteTranslation::class;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'quotes';
    protected $fillable = [
        'day'
    ];
    public function quote_translations()
    {
        return $this->hasMany(self::QUOTE_TRANSLATION, 'quote_id');
    }
    public function quote_translation()
    {
        return $this->hasOne(self::QUOTE_TRANSLATION, 'quote_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->quote_translations()->delete();
        });
    }
}
