<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
/**
 * Cms Model
 */


class Dropdown extends BaseModel   {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'dropdown';
    protected $fillable = [
      //  'image', // Add your image column here
    ];


    public function dropdown_translations()
    {
        return $this->hasMany('App\Models\StandoutTranslation');
    }
    public function dropdown_translation()
    {
        return $this->hasOne('App\Models\StandoutTranslation');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->dropdown_translations()->delete();
        });
    }

}
