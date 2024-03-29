<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['value'];

    public function getValueAttribute()
    {
        return isset($this->attributes['value']) ? $this->attributes['value'] : '';
    }
}
