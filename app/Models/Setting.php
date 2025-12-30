<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Setting extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $table = 'settings';

    protected $fillable = [
        'websiteTitle', 'logo', 'favicon', 'isMaintenanceMode', 'UploadServerType', 'pageLimit', 'version', 'footer', 'header', 'secondaryColor', 'primaryColor',
        'dateFormat', 'customCss', 'thirdPartyJs', 'googleAnalytics', 'companyAddress', 'companyPhone', 'companyEmail','dateFormat','password_generation','two_factor_authentication','otp_verification_mode'
    ];


    public function tapActivity(Activity $activity, string $eventName)
    {

        $section_name = Request::input('section');
        $auth_user = Auth::guard('admin')->user()->full_name;
        $log_name = ucfirst($auth_user);
        $activity->log_name = $log_name;
        $activity->section_name = $section_name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()
        ->logOnly(array_diff($this->fillable, ['logo']));
    }
}
