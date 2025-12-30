<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BlockUser extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'block_users';
    protected $fillable = [
        'user_id1','user_id2'
    ];


}
