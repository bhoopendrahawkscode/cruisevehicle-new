<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\QuestionHistory;
use App\Services\GeneralService;
class UserResource extends JsonResource
{

    public $extras  = [];
    public function __construct($resource, $extras) {
        // Ensure we call the parent constructor
        parent::__construct($resource);
        $this->extras   = $extras;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'mobile_no' => $this->mobile_no,
            'mobile_verified' => $this->mobile_verified,
            'email_verified' => $this->email_verified,
            'notification_status' => $this->notification_status,
            'unreadNotificationCount' => count($this->unreadNotificationCount),
            'image' => $this->thumbImage,
            'extras' => $this->extras,
        ];
    }
}
