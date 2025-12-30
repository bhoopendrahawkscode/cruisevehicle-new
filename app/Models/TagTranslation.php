<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
class TagTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "tag_translations";
    protected $fillable = ["tag_id", "language_id", 'name','slug', 'created_at', 'updated_at'];

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public static function getActiveTags($langId){
        return self::select('tag_id as id', 'name','slug')
        ->where('language_id', $langId)
        ->orderBy('name')->get();
    }

    public static function getTagNameFromSlug($slug = ''){
        return self::where('slug', $slug)->first();
    }
}
