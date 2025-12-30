<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class CommonTranslation extends BaseModel
{
    use SoftDeletes;

    protected static $global = 'default_table';
    public $timestamps = true;
    protected $fillable = ["language_id",'name','created_at','updated_at'];

    /**
     * Belongs to Many Relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function common_translations()
    {
        return $this->belongsTo('App\Models\Common');
    }

    public function getTable() {
        return self::$global ;
    }
    public static function setGlobalTable($table) { //PGupta
        self::$global = $table;
    }

}
