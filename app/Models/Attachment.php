<?php

namespace App\Models;

use App\Models\BaseModel;
class Attachment extends BaseModel
{
    protected $table = 'attachments';
    protected $fillable = [
        'type','url'
    ];
    public static function deleteFile($url = null){
        Self::where('url',$url)->delete();
    }
}
