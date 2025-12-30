<?php
namespace App\Models;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
/**
 * Cms Model
 */
class MasterDropdown extends Eloquent   {

	use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'masterdropdowns';

	public function getThumbImageAttribute()
    {

        if(ImageService::exists(Config::get('constants.MASTER_DROPDOWN_FOLDER').$this->image)
         && $this->image != NULL){
            $image = ImageService::getImageUrl(Config::get('constants.MASTER_DROPDOWN_FOLDER').'thumb_'.$this->image);
        } else {

            $image = url(Constant::NO_IMAGE);
        }
        return $image;
    }
}
