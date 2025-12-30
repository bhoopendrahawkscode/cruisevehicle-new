<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class CommentReport extends BaseModel
{
    use HasFactory;
    protected $table = 'comment_reports';

    protected $fillable = [
        'user_id','comment_id','type','message'
    ];


}
