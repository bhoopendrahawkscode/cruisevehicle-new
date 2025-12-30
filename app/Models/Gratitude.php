<?php

namespace App\Models;

use App\Services\ImageService;
use App\Constants\Constant;
use App\Models\BaseModel;
use App\Models\GratitudeAttachment;

class Gratitude extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'gratitudes';
    protected $fillable = [
        'user_id','title','description','audio','image','video'
    ];

    public function gratitude_attachments()
    {
        return $this->hasMany(GratitudeAttachment::class, 'gratitude_id');
    }


}
