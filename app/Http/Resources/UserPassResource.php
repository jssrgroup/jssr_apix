<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPassResource extends JsonResource
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
            'id' => $this->INDX,
            'bidno' => $this->BIDDER_NO,
            'username' => $this->LOG_USER,
            'name' => $this->FULL_NAME,
            'mobile' => $this->MOBILE_NO,
            'email' => $this->E_MAIL,
        ];
                // "INDX": 338,
        // "BIDDER_NO": "040",
        // "MOBILE_NO": "0818857359",
        // "LOG_USER": "kaewy",
        // "E_MAIL": "somboon816@hotmail.com",
    }
}
