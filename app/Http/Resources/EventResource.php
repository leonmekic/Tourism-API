<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'type'          => $this->type,
            'description'   => $this->description,
            'date'          => $this->date,
            'working_hours' => WorkingHoursResource::collection($this->whenLoaded('workingHours')),
            'address'       => $this->address,
            'created_at'    => $this->created_at,
        ];
    }
}
