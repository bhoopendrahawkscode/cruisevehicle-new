<?php 
namespace App\Models; 
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cms Model
 */
class Support extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'supports';
	
	public function comments(): HasMany
    {
        return $this->hasMany(SupportComment::class);
    }
	public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

	
}
