<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
class CommunityResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $communityTypes     =   Constant::COMMUNITIES;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'type' => $this->type,
            'typeName' => isset($communityTypes[$this->type])?$communityTypes[$this->type]:'',
            'code' =>  $this->code,
            'image' => $this->thumbImage,
            'question'=>$this->question,
            //'noOfPeople'=>$this->people,
            'noOfPeoples'=>$this->totalPeople,
            'created_at' => $this->created_at,
        ];
    }
}
