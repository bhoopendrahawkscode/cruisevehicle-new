<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class Tag extends BaseModel
{

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'tags';

    protected $fillable = ['status'];


    public function tag_translations()
    {
        return $this->hasMany('App\Models\TagTranslation', 'tag_id');
    }
    public function tag_translation()
    {
        return $this->hasOne('App\Models\TagTranslation', 'tag_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->tag_translations()->delete();
        });
    }

    public static function deleteTo($id)
   {
        self::where('id', $id)->first();


    }
}
