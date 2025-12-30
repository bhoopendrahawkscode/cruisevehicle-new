<?php
namespace App\Traits;

use App\Services\GeneralService;
use Auth;
trait TimezoneConvert
{
    /**
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($valueC)
    {
        return GeneralService::convertTimeZoneToUserTimeZoneDateTime($valueC);
    }

    public function getUpdatedAtAttribute($valueU)
    {
        return GeneralService::convertTimeZoneToUserTimeZoneDateTime($valueU);
    }

    public function getCreatedAttribute()
    {
        return GeneralService::convertDateTimeFormat($this->created_at);
    }

}
?>
