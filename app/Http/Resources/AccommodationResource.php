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
            'general_info'  => GeneralInfoResource::collection($this->whenLoaded('generalInformation')),
            'working_hours' => WorkingHoursResource::collection($this->whenLoaded('workingHours')),
            'reviews'       => ReviewsResource::collection($this->whenLoaded('reviews')),
            'rooms'         => RoomResource::collection($this->whenLoaded('rooms')),
            'created_at'    => $this->created_at,
        ];
    }
}
