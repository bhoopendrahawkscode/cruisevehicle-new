<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class AudioResource extends JsonResource
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
            'audio' => $this->audio,
            'duration' => $this->duration,
            'image' => $this->thumbImage,
        ];
    }


}
