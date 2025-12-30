<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class CommunityReport extends BaseModel
{
    use HasFactory;
    protected $table = 'community_reports';


    protected $fillable = [
        'user_id','community_id','type','message'
    ];



}
