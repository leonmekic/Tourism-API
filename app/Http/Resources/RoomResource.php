<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_number' => $this->room_number,
            'capacity'    => $this->capacity,
            'calendar'    => $this->when($this->calendar, $this->calendar),
            'bookings'    => BookingResource::collection($this->whenLoaded('bookings'))
        ];
    }
}
