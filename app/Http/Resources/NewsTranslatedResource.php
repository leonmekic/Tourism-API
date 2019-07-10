<?php

namespace App\Http\Resources;

use App\Models\Translation;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsTranslatedResource extends JsonResource
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
            'name'    => $this->getTitle(),
            'body'    => $this->getBody(),
            'reviews' => ReviewsResource::collection($this->whenLoaded('reviews')),
            'photo'   => AttachmentsResource::collection($this->whenLoaded('attachments')),
        ];
    }

    protected function getTitle()
    {
        if (app()->getLocale() == 'en'){
            return $this->title;
        }

        return Translation::getTranslation($this->resource, 'title', app()->getLocale());
    }

    protected function getBody()
    {
        if (app()->getLocale() == 'en'){
            return $this->body;
        }

        return Translation::getTranslation($this->resource, 'body', app()->getLocale());
    }

}
