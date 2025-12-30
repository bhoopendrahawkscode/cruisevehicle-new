<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class GratitudeResource extends JsonResource
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
            'description' => $this->description,
            'attachments' =>  $this->gratitude_attachments,
            'created_at' => $this->created_at,
        ];
    }
}
