<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class CMSResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->cms_translation->title,
            'body' => $this->cms_translation->body,


        ];
    }
}
