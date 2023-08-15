<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\UserAdmin;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // "id": 29,
        // "ref_id": 0,
        // "doc_id": 14,
        // "doc_type_id": 9,
        // "ref_user_id": 18,
        // "ref_dep_id": 3,
        // "dep_desc": "บัญชี",
        // "image_name": "cccc",
        // "file_name": "KYcMA3EtXE7AxPcE6tGVOpfzPRKlY6Oumf0uL9G8.png",
        // "create_doc_date": "09/08/2023",
        // "expire_doc_date": "16/08/2023",
        // "document_expire": "07/11/2023",
        // "document_expire_type": "90 DAY",
        // "doctype_expire": "09/08/2024",
        // "doctype_expire_type": "1 YEAR",
        // "remain_date": 1,
        // "doc_remain_date": 84,
        // "type_remain_date": 360
        return [
            'id' => $this->id,
            'ref_id' => $this->ref_id,
            // 'ref' => $this->getRefName($this->ref_id, $this->doc_type_id),
            'doc_id' => $this->doc_id,
            'doc_id' => $this->getDocTypeName($this->doc_id),
            // 'doc_type_id' => $this->doc_type_id,
            'doc_type_id' => $this->getDocTypeName($this->doc_type_id),
            'ref_user_id' => $this->ref_user_id,
            'ref_user' => $this->getUserManagementName($this->ref_user_id),
            // 'ref_dep_id' => $this->ref_dep_id,
            'ref_dep_id' => $this->dep_desc,
            'image_name' => $this->image_name,
            'file_name' => $this->file_name,
            'create_doc_date' => $this->create_doc_date,
            'expire_doc_date' => $this->expire_doc_date,
            'document_expire' => $this->document_expire,
            'document_expire_type' => $this->document_expire_type,
            'doctype_expire' => $this->doctype_expire,
            'doctype_expire_type' => $this->doctype_expire_type,
            'remain_date' => $this->remain_date,
            'doc_remain_date' => $this->doc_remain_date,
            'type_remain_date' => $this->type_remain_date,
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
        $docType = DocumentType::find($id);
        return isset($docType['desc']) ? $docType['desc'] : null;
    }

    function formatDate($date)
    {
        // Create a DateTime object from the date string
        $dateTimeObj = new DateTime($date);

        return $dateTimeObj->format('d/m/Y');
    }
}
