<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return   [
            'user_id' => 'required',
            'dep_id' => 'required',
            'role_id' => 'required',
            'status' => '',
            'is_delete' => '',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user_id' => 'user',
            'dep_id' => 'department',
            'role_id' => 'role',
            'is_delete' => 'is delete',
        ];
    }
}
