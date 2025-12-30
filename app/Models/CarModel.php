<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Models\Scopes\StatusScope;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends BaseModel
{
    use SoftDeletes;
    protected $table = 'models';
    protected $fillable =['id','brand_id','name','image','year','status' ];

    public function getThumbImageAttribute()
    {
        if ($this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.IMAGE_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    /**
     * Get the user that owns the Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


}
