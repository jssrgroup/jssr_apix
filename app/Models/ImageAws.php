<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAws extends Model
{
    use HasFactory;
    protected $fillable = [
        "image_name", "cus_id",  "file_name", "expire_date_at"
    ];
}
