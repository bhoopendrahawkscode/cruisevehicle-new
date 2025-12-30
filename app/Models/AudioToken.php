<?php

namespace App\Models;

use App\Models\BaseModel;

class AudioToken extends BaseModel
{

    protected $table = 'audio_tokens';
    protected $fillable = [
        'token','url'
    ];

    public static function getAudioTokenByToken($token = ''){
        return self::where('token', $token)->first();
    }

}
