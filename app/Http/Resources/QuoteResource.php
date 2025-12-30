<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class QuoteResource extends JsonResource
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
			'day' => $this->day,
            'name' => $this->name,
            'written_by' => $this->written_by,
        ];
    }


}
