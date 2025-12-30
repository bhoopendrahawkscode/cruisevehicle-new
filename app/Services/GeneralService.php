<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Models\UsersOtp;
use Auth, Hash;
use App\Constants\Constant;
use App\Jobs\SendTwilioMessageJob;
use App\Jobs\SendEmailJob;
use Spatie\Activitylog\Contracts\Activity;

class GeneralService
{
    public static function convertTimeZoneToUserTimeZoneDateTime($value)
    {
        try {
            $utcDateTime = new \DateTime($value, new \DateTimeZone('UTC'));
            $utcDateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        } catch (\Exception) {
            $utcDateTime = new \DateTime($value, new \DateTimeZone('UTC'));
            $utcDateTime->setTimezone(new \DateTimeZone('UTC'));
        }
        return $utcDateTime->format('Y-m-d H:i:s');
    }

    public static function convertDateTimeFormat($dateValue)
    {
        try {
            $configDateFormat = (!empty(self::getSettings('dateFormat'))) ? self::getSettings('dateFormat') : 'Y-m-d H:i:s';
            $utcDateTime = new \DateTime($dateValue, new \DateTimeZone('UTC'));
            $utcDateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        } catch (\Exception) {
            $utcDateTime = new \DateTime($dateValue, new \DateTimeZone('UTC'));
            $utcDateTime->setTimezone(new \DateTimeZone('UTC'));
        }

        return $utcDateTime->format($configDateFormat);
    }



    public static function getSettings($key = null)
    {
        $settingsCache =  Cache::get('settingsCache');
        if (empty($settingsCache)) {
            $row = Setting::findOrFail(1);
            Cache::put('settingsCache', $row);
        }

        $settingsCache =  Cache::get('settingsCache');
        if (isset($settingsCache[$key])) {
            return $settingsCache[$key];
        } else {
            return '';
        }
    }

    public static function generateOtp($digits = 6)
    {
        //return '1234';
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        if(self::getSettings('otp_verification_mode')==Constant::OTP_PRODUCTION_MODE){
            while ($i < $digits) {
                //generate a random number between 0 and 9.
                $pin .= random_int(0, 9);
                $i++;
            }
        } else {    //production
            $pin = 1234;
        }

        return $pin;
    }
    public static function getLanguages()
    {

        $activeLanguages =  Cache::get('activeLanguages');
        if (empty($activeLanguages)) {
            $activeLanguages =  Language::where('status', 1)->get();
            Cache::put('activeLanguages', $activeLanguages);
        }
        return Cache::get('activeLanguages');
    }
    public function formatDuration($seconds)
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return $minutes . 'm,' . $seconds . 's';
    }

    public static function getUserOtp()
    {
        $otp = GeneralService::generateOtp();
        $otpDetails  = UsersOtp::where(['mobile_no' => Auth::user()->country_code
            . Auth::user()->mobile_no])->first();
        if (empty($otpDetails)) {
            UsersOtp::create(['mobile_no' => Auth::user()->country_code
                . Auth::user()->mobile_no, 'otp' => $otp, 'expired_at' => time() + 120]);
        } else {
            UsersOtp::where('mobile_no', Auth::user()->country_code
                . Auth::user()->mobile_no)->update(['otp' => $otp, 'expired_at' => time() + 120]);
        }
        return $otp;
    }

    public static function getGeneralOtp($countryCode, $mobileNo)
    {
        $otp = GeneralService::generateOtp();
        $otpDetails  = UsersOtp::where(['mobile_no' => $countryCode. $mobileNo])->first();
        if (empty($otpDetails)) {
            UsersOtp::create(['mobile_no' => $countryCode. $mobileNo, 'otp' => $otp, 'expired_at' => time() + 120]);
        } else {
            UsersOtp::where('mobile_no', $countryCode. $mobileNo)->update(['otp' => $otp, 'expired_at' => time() + 120]);
        }
        $phoneNumber = '+' . $countryCode . $mobileNo;
        $msg = __('api.OTP_TEXT') . " " . $otp;
        if(self::getSettings('otp_verification_mode')==Constant::OTP_PRODUCTION_MODE){
             dispatch(new SendTwilioMessageJob($phoneNumber, $msg));
        }

        return $otp;
    }

    public static function  generateRandomCode($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return strtoupper($code);
    }

    public static function getPassword($obj, $request)
    {
        $password_generation = GeneralService::getSettings('password_generation');
        if(Constant::ALLOW_SUB_ADMIN_PASSWORD_CREATE && $password_generation==Constant::MANUAL_FACTOR){
            $obj->password      = $request->password;
        } else {
            $obj->password      = self::generateRandomCode(2) . "a123$#";
        }
        return $obj;
    }

    public static function sendSubAdminPassword($emailData)
    {
        try {
            dispatch(new SendEmailJob($emailData));
        } catch (\Exception $e) {
            echo  $e->getMessage();
        }
    }
    public static function mergePhoneNumberValidation($validate)
    {
        if (Constant::ALLOW_SUB_ADMIN_PHONE_NO) {
            $validate       =   array_merge($validate, ['country_code' => 'required', 'mobile_no'  => 'required|digits_between:6,12|numeric|phone_unick']);
        }
        return $validate;
    }
    public static function formatSecondsToHoursMinutes($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf("%02d:%02d", $hours, $minutes);
    }

    public static function UpdateStatusActivityLog(Object $effected_model, string $event, array $properties, bool $is_multi_lang=false): void
    {


        $auth_user = Auth::guard('admin')->user()->full_name;

        activity()
            ->performedOn($effected_model)
            ->tap(function (Activity $activity) use ($auth_user, $is_multi_lang) {
                $section_name = \Illuminate\Support\Facades\Request::input('section');
                $log_name = ucfirst($auth_user);
                $activity->log_name = $log_name;
                $activity->is_multi_language =$is_multi_lang;
                $activity->section_name = $section_name;
            })
            ->withProperties($properties)
            ->causedBy(Auth::guard('admin')->user())
            ->event($event)
            ->log("Status $event $auth_user");

    }


}
