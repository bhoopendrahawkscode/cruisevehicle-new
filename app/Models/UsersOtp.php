<?php

namespace App\Models;

use App\Models\BaseModel;

class UsersOtp extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_otp';

	 /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = ['mobile_no','otp','expired_at'];
}
