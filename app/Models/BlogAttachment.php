<?php

namespace App\Models;
use App\Constants\Constant;
use App\Models\BaseModel;
use App\Services\ImageService;
use App\Services\ModelService;
class BlogAttachment extends BaseModel
{
    protected $table = 'blog_attachments';
    protected $fillable = [
        'type','url','blog_id'
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
