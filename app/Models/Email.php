<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $table = 'email';
    protected $primaryKey = 'mail_ID';
    // protected $connection = 'mysql3';

    protected $fillable = [];

    protected $guarded = [];

    protected $hidden = [
        '', '', '', '', '', '', '',
    ];

    public $timestamps = false;
}
