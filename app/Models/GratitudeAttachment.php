<?php

namespace App\Models;
use App\Constants\Constant;
use App\Models\BaseModel;
use App\Services\ImageService;
use App\Services\ModelService;

class GratitudeAttachment extends BaseModel
{
    protected $table = 'gratitude_attachments';
    protected $fillable = [
        'type','url','gratitude_id'
    ];


    public function getUrlAttribute(){
        return ModelService::getUrlAttribute($this);
    }

    public function getFileNameAttribute(){
        return ['type'=>$this->attributes['type'],'url'=>$this->attributes['url']];
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            ModelService::deleteAttachment($model);
        });
    }



}
