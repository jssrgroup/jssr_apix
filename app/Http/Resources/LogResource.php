<?php

namespace App\Http\Resources;

use App\Models\Document;
use App\Models\UserAdmin;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
            'userId' => $this->user_id,
            'user' => $this->getUserManagementName($this->user_id),
            'docId' => $this->doc_id,
            'doc' => $this->getDocumentName($this->doc_id),
            'class' => $this->getClass($this->class),
            'method' => $this->getMethod($this->method),
            'behavior' => $this->behavior,
            'createdate' => $this->formatDate($this->created_at),
        ];
    }


    function getUserManagementName($id)
    {
        $userAdmin = UserAdmin::find($id);
        return $userAdmin['FULL_NAME'];
    }

    function getDocumentName($id)
    {
        $doc = Document::find($id);
        return isset($doc['image_name']) ? $doc['image_name'] : null;
        // return $id;
    }

    function formatDate($date)
    {
        // Create a DateTime object from the date string
        $dateTimeObj = new DateTime($date);

        return $dateTimeObj->format('d/m/Y H:i:s');
    }
    
    function getClass($text)
    {
        $explodedArray = explode("\\", $text);
        $lastElement = end($explodedArray);
        return $lastElement;
    }
    
    function getMethod($text)
    {
        $explodedArray = explode("::", $text);
        $lastElement = end($explodedArray);
        return $lastElement;
    }
}
