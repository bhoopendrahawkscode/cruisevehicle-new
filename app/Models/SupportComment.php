<?php
namespace App\Models;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BaseModel;


/**
 * Cms Model
 */
class SupportComment extends BaseModel   {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'support_comments';

	public function user()
    {
        return  $this->belongsTo('App\Models\User', 'user_id')->select('id', 'full_name','email');
    }


}
