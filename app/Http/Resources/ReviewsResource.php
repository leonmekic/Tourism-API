<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
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
            'id'          => $this->id,
            'stars'       => $this->stars,
            'comment'     => $this->comment,
            'created_at'  => $this->created_at,
            'user'        => new UserResource($this->whenLoaded('user')),
            'attachments' => AttachmentsResource::collection($this->whenLoaded('attachments'))
        ];
    }

}
