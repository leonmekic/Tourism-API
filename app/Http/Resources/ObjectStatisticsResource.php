<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectStatisticsResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'average_rating'    => number_format($this->average_rating, 1),
            'number_of_reviews' => $this->number_of_reviews,
            'rating_count'      => $this->rating_count
        ];
    }
}
