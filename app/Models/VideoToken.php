<?php

namespace App\Models;

use App\Models\BaseModel;

class VideoToken extends BaseModel
{

    protected $table = 'video_tokens';
    protected $fillable = [
        'token','url'
    ];

    public static function getVideoTokenByToken($token = ''){
        return self::where('token', $token)->first();
    }

}
