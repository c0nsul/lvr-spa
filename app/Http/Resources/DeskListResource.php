<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeskListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'=> $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'cards' => CardResource::collection($this->cards),
        ];
    }
}
