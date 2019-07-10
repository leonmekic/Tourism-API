<?php

namespace App\Http\Resources;

use App\Models\Translation;
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
            'title'   => $this->getTitle(),
            'body'    => $this->getBody(),
            'reviews' => ReviewsResource::collection($this->whenLoaded('reviews')),
            'photo'   => AttachmentsResource::collection($this->whenLoaded('attachments')),
        ];
    }

    protected function getTitle()
    {
        return $this->getTranslatedAttribute('title', app()->getLocale());
    }

    protected function getBody()
    {
        return $this->getTranslatedAttribute('body', app()->getLocale());
    }
}
