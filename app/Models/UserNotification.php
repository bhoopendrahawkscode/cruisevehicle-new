<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Eloquent;
use App\Services\GeneralService;

class UserNotification extends BaseModel
{
    use HasFactory;
    protected $table = 'user_notifications';

    public function user()
    {
        return  $this->belongsTo('App\Models\User', 'user_id')->select('id', 'full_name');
    }

}
