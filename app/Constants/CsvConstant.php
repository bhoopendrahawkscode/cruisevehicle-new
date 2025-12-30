<?php

namespace App\Constants;

class CsvConstant
{
    const COMMON_EXCLUDE_FIELDS = ['image','deleted_at'];
    const USER_EXCLUDE_FIELDS = ['password', 'full_name','remember_token','auth_token','apikey','latitude','longitude','notification_status','device_type','device_token','country'];

    const AUDIO_EXCLUDE_FIELDS = ['audio','category'];
    const VIDEO_EXCLUDE_FIELDS = ['video'];
    const SETTING_EXCLUDE_FIELDS = ['logo'];


}
