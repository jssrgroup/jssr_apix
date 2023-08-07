<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\UserAdmin;
use Illuminate\Http\Resources\Json\JsonResource;

class UserManagementResource extends JsonResource
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
            'depId' => $this->dep_id,
            'dep' => $this->getDepartmentName($this->dep_id),
            'roleId' => $this->role_id,
            'role' => $this->getRoleName($this->role_id),
            'status' => $this->getStatus($this->status),
            'isDelete' => $this->is_delete,
        ];
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

    function getRoleName($id)
    {
        $name = '';
        switch ($id) {
            case 1:
                $name = 'Superadmin';
                break;
            case 2:
                $name = 'Admin';
                break;
            case 3:
                $name = 'User';
                break;
            default:
                $name = '';
                break;
        }
        return $name;
    }

    function getStatus($id)
    {
        $name = '';
        switch ($id) {
            case 1:
                $name = 'ใช้งาน';
                break;
            default:
                $name = 'ไม่ใช้งาน';
                break;
        }
        return $name;
    }
}
