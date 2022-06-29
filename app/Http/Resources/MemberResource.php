<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'username' => $this->USER_NAME,
            'cusId' => $this->CUSTOMER_ID,
            'name' => $this->NAME,
            'companyName' => $this->COMPANY_NAME,
            'address' => $this->ADDRESS1,
            'language' => $this->LANGUAGE,
            'exRate' => $this->EX_RATE,
            'mobile' => $this->MOBILE,
            'phone' => $this->PHONE,
            'fax' => $this->FAX,
            'email' => $this->E_MAIL,
            'photo' => $this->MEMBER_PIC,
        ];
          // "INDX": 146,
    // "CUSTOMER_ID": "JSSR30018",
    // "USER_NAME": "sanchai",
    // "NAME": "Sanchai Zhang",
    // "COMPANY_NAME": "0",
    // "ADDRESS1": "31 ตรอกศาลเจ้าโกบ๊อ 1  ซ.สมเด็จพระเจ้าตากสิน 34 แขวงบุคคลโล เขตธนบุรี จ.กรุงเทพฯ",
    // "LANGUAGE": "TH",
    // "EX_RATE": "THB",
    // "MOBILE": "",
    // "PHONE": "01-823-4577",
    // "FAX": "02-451-6228",
    // "EMAIL": "thaikijtrading@yahoo.com",
    // "MEMBER_PIC": "",
    }
}
