<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Transaction extends BaseModel
{

    use SoftDeletes,LogsActivity;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'transactions';
    
    public function tapActivity(Activity $activity, string $eventName)
    {
          $section_name = Request::input('section');
        $auth_user = Auth::guard('admin')->user()->full_name;
        $log_name = ucfirst($auth_user);
        $activity->log_name = $log_name;
        $activity->section_name =$section_name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()
        ->logFillable();
    }

}
