<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomOccupationStatisticsResource extends JsonResource
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
            'room_number'           => $this->room_number,
            'total_days'            => $this->total_days,
            'occupation'            => $this->occupation,
            'occupation_percentage' => $this->occupation_percentage
        ];
    }
}

