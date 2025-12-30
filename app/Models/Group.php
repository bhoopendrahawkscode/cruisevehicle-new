<?php



namespace App\Models;
use App\Models\BaseModel;
/**
 * Class UsersMedia
 *
 * @property int $id
 * @property int $user_id
 * @property int $media_type
 * @property string $file
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\User $user
 *
 * @package App
 */
class Group extends BaseModel
{

	protected $table = 'groups';
    protected $primaryKey = 'id';



}
