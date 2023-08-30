<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        "image_name", "ref_id", "ref_dep_id",  "file_name", "expire_date_at",
        "ref_doc_type_id", "ref_user_id", "ref_doc_id",
        "updated_by", "is_delete", "deleted_by", "deleted_at"
    ];
}
