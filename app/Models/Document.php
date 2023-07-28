<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;    
    protected $fillable = [
        "image_name", "ref_id", "ref_dep_id",  "file_name", "expire_date_at"
    ];
}
