<?php

namespace App\Models;
use App\Models\Question;
use App\Models\BaseModel;

class QuestionHistory extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    public static function updateMoodScore($userId = 0){
        $questionIds = Question::where('status', 1)->pluck('id','id')->toArray();
        $sum        =   self::whereIn('id', $questionIds)->where(['user_id'=>$userId])->sum('score');
        $count      =   self::whereIn('id', $questionIds)->where(['user_id'=>$userId])->count();
        return $count > 0 ? $sum / $count : 0;
    }

    protected $table = 'question_histories';
    protected $fillable = [
    ];


}
