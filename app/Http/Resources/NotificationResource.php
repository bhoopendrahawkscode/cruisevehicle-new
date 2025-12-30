<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class NotificationResource extends JsonResource
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
			'title' => $this->title,
            'created_at' => $this->created_at,
			'type' => $this->type,
            'link_id' => $this->link_id,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }


}
