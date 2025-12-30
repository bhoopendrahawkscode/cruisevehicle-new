<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BlogUser extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'blog_users';
    protected $fillable = [
        'user_id','blog_id','archive','bookmark'
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
