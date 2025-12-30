<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\BaseModel;


/**
 * Cms Model
 */
class Permission extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = false;
    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'action',
        'group_name',
    ];



  public function roles(): BelongsToMany
  {
      return $this->belongsToMany(Role::class, 'role_permission');
  }
}
