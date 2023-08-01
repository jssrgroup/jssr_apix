<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\Member;
use App\Models\UserAdmin;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'ref_id' => $this->ref_id,
            'ref' => $this->getRefName($this->ref_id, $this->ref_doc_type_id),
            'ref_dep_id' => $this->ref_dep_id,
            'ref_dep' => $this->getDepartmentName($this->ref_dep_id),
            'ref_doc_type_id' => $this->ref_doc_type_id,
            'ref_doc_type' => $this->getDocTypeName($this->ref_doc_type_id),
            'image_name' => $this->image_name,
            'file_name' => $this->file_name,
            'expire_date_at' => $this->formatDate($this->expire_date_at),
            'ref_user_id' => $this->ref_user_id,
            'ref_user' => $this->getUserManagementName($this->ref_user_id),
        ];
    }

    function getRefName($id, $docTypeId)
    {
        $ref = '';
        switch ($docTypeId) {
                // case 2:
                //     $department = Department::find($id);
                //     $ref = $department['desc'];
                //     break;
            case 3:
                $member = Member::find($id);
                $ref = $member['NAME'];
                break;
        }
        return $ref;
    }

    function getDepartmentName($id)
    {
        $department = Department::find($id);
        return $department['desc'];
    }

    function getUserManagementName($id)
    {
        $userAdmin = UserAdmin::find($id);
        return $userAdmin['FULL_NAME'];
    }

    function getDocTypeName($id)
    {
        $name = 'เอกสารอื่นๆ';
        switch ($id) {
            case 1:
                $name = 'เอกสารบริษัท';
                break;
            case 2:
                $name = 'เอกสารพนักงาน';
                break;
            case 3:
                $name = 'เอกสารลูกค้า';
                break;
            default:
                $name = 'เอกสารอื่นๆ';
                break;
        }
        return $name;
    }

    function formatDate($date)
    {
        // Create a DateTime object from the date string
        $dateTimeObj = new DateTime($date);

        return $dateTimeObj->format('d/m/Y');
    }
}
