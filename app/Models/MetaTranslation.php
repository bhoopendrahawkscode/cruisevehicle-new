<?php

namespace App\Models;

use App\Constants\Constant;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MetaTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['meta_title', 'meta_description', 'image', 'meta_key', 'language_id','meta_id','id'];

    public function getThumbImageAttribute()
    {

        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.META_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    
}
