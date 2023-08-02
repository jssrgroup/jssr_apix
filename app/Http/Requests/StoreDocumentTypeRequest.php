<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreDocumentTypeRequest extends FormRequest
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
            'dep_id' => 'required',
            'code' => '',
            'desc' => 'required',
            'parent' => '',
            'pattern' => '',
            'expire' => 'required',
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
            'dep_id' => 'department',
            'desc' => 'description',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'dep_id.required' => 'Department is required',
            // 'desc.required' => 'Desc is required',
            'expire.required' => 'Expire is required',
            // 'required' => ':attribute ห้ามว่าง.',
        ];
    }
}
