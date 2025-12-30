<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoryTranslation extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;
	protected $table = "category_translations";
    protected $fillable = ["category_id", "language_id",'name','slug','created_at','updated_at'];


    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public static function getActiveCategories($langId){
        return self::select('category_id as id', 'name','slug')
        ->where('language_id', $langId)
        ->orderBy('name')->get();
    }

    public static function getCategoryIdFromSlug($slug = ''){
        return self::where('slug', $slug)->first();
    }
}
