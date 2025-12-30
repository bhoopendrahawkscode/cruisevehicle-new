<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class AudioHistory extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'audio_histories';
    protected $fillable = [
    ];

    public static function getHistoryCountsByIds($audioIds = []){
        return self::select('audio_id', \DB::raw('COUNT(*) as count'))->whereIn('audio_id', $audioIds)
            ->groupBy('audio_id')
            ->get()
            ->pluck('count', 'audio_id')->toArray();
    }
}
