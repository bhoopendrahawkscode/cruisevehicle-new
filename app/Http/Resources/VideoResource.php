<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class VideoResource extends JsonResource
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
			'name' => $this->name,
            'artist' => $this->artist,
            'category' => $this->category,
            'video' => $this->video,
            'duration' => $this->duration,
        ];
    }


}
