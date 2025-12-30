<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Language Model
 */
class Language extends Eloquent
{
    protected $table = 'languages';
    protected $fillable =['locale','name','status'];
}
