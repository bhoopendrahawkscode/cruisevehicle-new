<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Config;
use App\Constants\Constant;
use App\Models\Scopes\StatusScope;
use App\Services\ImageService;
use App\Traits\StatusScopeTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends BaseModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'image', 'status'];

    public function getThumbImageAttribute()
    {
        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.IMAGE_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }

    /**
     * Get all of the comments for the Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models(): HasMany
    {
        return $this->hasMany(Model::class, 'brand_id');
    }
}
