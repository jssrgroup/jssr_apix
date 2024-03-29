<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\DocumentType;
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
            'depDesc' => $this->getDepartmentName($this->dep_id),
            'code' => $this->code,
            'desc' => $this->desc,
            'parent' => $this->parent,
            'parentDesc' => $this->getParentName($this->parent),
            'pattern' => $this->pattern,
            'expire' => $this->expire,
            'expire_type' => $this->expire_type,
            'numAi' => $this->num_ai,
        ];
        // "", "", "", "", "", "", "",
    }


    function getDepartmentName($id)
    {
        $department = Department::find($id);
        return $department['desc'];
    }

    function getParentName($id)
    {
        $docType = DocumentType::where('id', $id)->first();
        return isset($docType['desc']) ? $docType['desc'] : null;
    }
}
