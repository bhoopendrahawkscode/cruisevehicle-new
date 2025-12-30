<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Comment extends BaseModel
{
    use HasFactory;
    protected $table = 'comments';

    protected $fillable = [
        'user_id','blog_id','content','attachment','attachment_type','users'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comment_reports()
    {
        return $this->hasMany(CommentReport::class, 'comment_id');
    }

    public function getUrlAttribute(){
        if($this->attachment_type == "1"){
            return Constant::S3_URL. $this->attributes['attachment'];
        }else{
            if (
                ImageService::exists(Constant::ATTACHMENT_FOLDER .$this->attributes['attachment'])
                && $this->attributes['attachment'] != null
            ) {
                $url = ImageService::getImageUrl(Constant::ATTACHMENT_FOLDER . $this->attributes['attachment']);
            } else {

                $url = "";
            }
            return $url;
        }
    }

    public function getFileNameAttribute(){
        return ['type'=>$this->attributes['attachment_type'],'url'=>$this->attributes['attachment']];
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            ModelService::deleteAttachment($model);
            $model->comment_reports()->delete();


        });
    }


}
