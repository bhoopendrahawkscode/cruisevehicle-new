<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
class CommunityUserResource extends JsonResource
{

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
            'user_id' => $this->user_id,
            'community_id' => $this->community_id,
            'answer' => $this->answer,
            'question' => $this->question,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'username' => $this->user->username,
            'image' => $this->user->thumbImage,
        ];
    }
}
