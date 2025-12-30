<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Cms Model
 */
class UserPermission extends BaseModel   {

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'user_permissions';



}
