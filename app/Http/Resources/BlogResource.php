<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
class BlogResource extends JsonResource
{
    public $data;
    public function __construct($resource, $data)
    {
        parent::__construct($resource);
        $this->data = $data;
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
            'user_id' => $this->user_id,
            'community_id' => $this->community_id,
            'content' => $this->content,
            'asking_help' => $this->asking_help,
            'offer_help' => $this->offer_help,
            'created_at' => $this->created_at,
            'username' =>  $this->user->username,
            'full_name' =>  $this->user->full_name,
            'user_image' =>  $this->user->thumbImage,
            'attachments' =>  $this->blog_attachments,
            'comments' =>  isset($this->data['comments'])?$this->data['comments']:[],
            'users' =>  isset($this->data['users'])?$this->data['users']:[]

        ];
    }
}
