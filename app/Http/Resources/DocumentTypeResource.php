<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'depId' => $this->dep_id,
            'code' => $this->code,
            'desc' => $this->desc,
            'parent' => $this->parent,
            'pattern' => $this->pattern,
            'expire' => $this->expire,
            'numAi' => $this->num_ai,
        ];
        // "", "", "", "", "", "", "",
    }
}
