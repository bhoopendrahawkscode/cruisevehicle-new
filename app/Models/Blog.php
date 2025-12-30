<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'blogs';

    protected $fillable = [
        'user_id','community_id','content','asking_help','status','last_reported_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blog_attachments()
    {
        return $this->hasMany(BlogAttachment::class, 'blog_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }
    public function blog_reports()
    {
        return $this->hasMany(BlogReport::class, 'blog_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->blog_reports()->delete();
        });
    }


}
