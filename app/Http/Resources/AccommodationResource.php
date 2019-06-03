<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccommodationResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'stars'         => $this->stars,
            'general info'  => GeneralInfoResource::collection($this->whenLoaded('generalInformation')),
            'working hours' => WorkingHoursResource::collection($this->whenLoaded('workingHours')),
            'reviews'       => ReviewsResource::collection($this->whenLoaded('reviews')),
            'created_at'    => $this->created_at,
        ];
    }
}
