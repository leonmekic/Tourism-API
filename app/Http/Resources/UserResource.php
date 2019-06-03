<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                   => $this->id,
            'name'                 => $this->name,
            'phone_number'         => $this->phone_number,
            'email'                => $this->email,
            'created_at'           => $this->created_at,
            'terms_and_conditions' => $this->terms_and_conditions
        ];
    }
}
