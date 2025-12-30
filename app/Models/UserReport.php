<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class UserReport extends BaseModel
{
    use HasFactory;
    protected $table = 'user_reports';

    protected $fillable = [
        'user_id','owner_id','type','message'
    ];

}
