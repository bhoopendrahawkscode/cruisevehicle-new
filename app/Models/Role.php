<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Role extends BaseModel
{
    use HasFactory, LogsActivity;
    protected $fillable =['name','slug'];
    protected function slug():Attribute{
        return Attribute::make(
            set:fn(string $value)=>Str::slug($value,'_')
        );

    }
    protected function name():Attribute{
        return Attribute::make(
            set:fn(string $value)=>strtolower($value),
        );

    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }


    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }


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
        ->logOnly(['name']);
    }


}
