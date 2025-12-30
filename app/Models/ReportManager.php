<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Eloquent;

class ReportManager extends Eloquent
{
    use HasFactory;
    protected $table = 'reports';

    public function reported_user()
    {
        return  $this->belongsTo('App\Models\User', 'to_id')->select('id', 'full_name', 'status');
    }

    public function reporting_user()
    {
        return  $this->belongsTo('App\Models\User', 'from_id')->select('id', 'full_name', 'status');
    }
}
