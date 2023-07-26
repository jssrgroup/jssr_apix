<?php

namespace App\Http\Controllers;

use App\Models\Email;

class EmailController extends Controller
{
    protected function email(){
        $email = Email::all();
        return response()->json([
            'status' => true,
            'status-code' => 200,
            'message' => 'get email all',
            'data' => $email
        ]);
    }
}
