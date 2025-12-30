<?php
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends BaseModel   {

	use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'posts';
    protected $fillable = [
        'image','author','categories'
    ];

	public function getThumbImageAttribute()
    {
        if(ImageService::exists(Config::get('constants.POST_FOLDER').$this->image)
         && $this->image != NULL){
            $image = ImageService::getImageUrl(Config::get('constants.POST_FOLDER').'thumb_'.$this->image);
        } else {
            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    public function post_translations()
    {
        return $this->hasMany('App\Models\PostTranslation','post_id');
    }

    public function post_translation()
    {
        return $this->hasOne('App\Models\PostTranslation','post_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class,'author');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->post_translations()->delete();
        });
    }

}
