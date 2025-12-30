<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
class MediaImage extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo','favicon'
    ];

    protected $loginType = 'SOCIAL';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function authAccessToken()
    {
        return $this->hasMany('\App\Models\OauthAccessToken');
    }

   
  public function getThumbImageAttribute()
    {

        if (
            ImageService::exists(Config::get('constants.MEDIA_FOLDER') . $this->image)
            && $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.MEDIA_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    public static function getUserDetails($userId = 0){
        return self::where('id',$userId)->first()->toArray();
    }
}
