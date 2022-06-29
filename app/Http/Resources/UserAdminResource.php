<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
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
            'username' => $this->USERNAME,
            'empId' => $this->EMP_ID,
            'name' => $this->FULL_NAME,
            'department' => $this->DEPARTMENT,
            'email' => $this->EMAIL,
            'phone' => $this->PHONE,
            'email' => $this->EMAIL,
            'accessList' =>  unserialize($this->ACCESS_LIST),
            'accessRegister' => unserialize($this->ACCESS_REGISTER),
            'accessInspection' => unserialize($this->ACCESS_INSPECTION),
            'accessGeneral' => unserialize($this->ACCESS_GENERAL),
        ];
            // "INDX": 146,
    // "USERNAME": "tanakphong",
    // "": "Phong@12345",
    // "DEPARTMENT": "IT",
    // "EMP_ID": "AIT61-09-047",
    // "FULL_NAME": "นายธนกร พงษ์สิงห์ TEST",
    // "STATUS": 1,
    // "STATUS_ADMIN": 0,
    // "LAST_LOGIN": null,
    // "ACCESS_LIST": "a:5:{i:0;s:1:\"1\";i:1;s:2:\"35\";i:2;s:2:\"36\";i:3;s:2:\"37\";i:4;s:2:\"38\";}",
    // "EMAIL": "",
    // "PHONE": "9530",
    // "ACCESS_REGISTER": "a:5:{i:0;s:1:\"9\";i:1;s:2:\"10\";i:2;s:2:\"12\";i:3;s:2:\"15\";i:4;s:2:\"18\";}",
    // "ACCESS_INSPECTION": "N;",
    // "ACCESS_GENERAL": null
    }
}
