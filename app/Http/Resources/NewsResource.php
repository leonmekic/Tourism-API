<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'name'    => $this->title,
            'body'    => $this->body,
            'reviews' => ReviewsResource::collection($this->whenLoaded('reviews')),
            'photo'   => AttachmentsResource::collection($this->whenLoaded('attachments'))
        ];
    }
}
