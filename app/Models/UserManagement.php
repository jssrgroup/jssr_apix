<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManagement extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id", "dep_id", "role_id", "status", "is_delete",
    ];
}
