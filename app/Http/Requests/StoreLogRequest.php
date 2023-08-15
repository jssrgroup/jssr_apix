<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return   [
            'class' => 'required',
            'method' => 'required',
            'behavior' => 'required',
            'user_id' => 'required',
        ];
    }
    
    public function attributes()
    {
        return [
            'class' => 'Class',
            'method' => 'Method',
            'behavior' => 'Behavior',
            'user_id' => 'User',
        ];
    }

    public function messages()
    {
        return [
            'class.required' => 'Class is required',
        ];
    }
}
