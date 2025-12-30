<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class BlogReport extends BaseModel
{
    use HasFactory;
    protected $table = 'blog_reports';

    protected $fillable = [
        'user_id','blog_id','type','message'
    ];

}
