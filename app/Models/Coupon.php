<?php

namespace App\Models;

use App\Constants\Constant;
use App\Services\ImageService;
use App\Traits\StatusAttribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Coupon extends BaseModel
{
    use HasFactory, LogsActivity,StatusAttribute;

    protected $fillable = [
        'name', 'offer_type', 'discount','min_order_value','discount_up_to',
        'code', 'start_date', 'expiry_date', 'maximum_uses', 'single_user_use_limit', 'image',
        'description', 'status'
    ];



    protected function startDate():Attribute{
        return Attribute::make(
            get:fn(string $val)=>Carbon::parse($val)->format('Y-m-d')
        );
    }
    protected function expiryDate():Attribute{
        return Attribute::make(
            get:fn(string $val)=>Carbon::parse($val)->format('Y-m-d')
        );
    }

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
        ->logOnly(array_diff($this->fillable, ['image']));
    }

    public function getThumbImageAttribute()
    {
        if ($this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.COUPON_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    
}
