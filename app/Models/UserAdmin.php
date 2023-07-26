<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserAdmin extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, HasFactory;
    protected $table = 'tb_user_admin';
    protected $primaryKey = 'INDX';
    protected $connection = 'mysql3';

    protected $fillable = [];

    protected $guarded = [];

    protected $hidden = [
        'PASSWORD', 'UPDATE_DATE', 'PASSWORD_OLD', 'LEVEL', 'LEVEL_POINT', 'LEVEL_KM', 'CHANGE_STATUS',
        'LEVEL_OUR', 'STATUS', 'STATUS_ADMIN', 'LAST_LOGIN', '', '', '',
        '', '', '', '', '', '', '',
        '', '', '', '', '', '', '',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'username' => $this->USERNAME,
            'empid' => $this->EMP_ID,
        ];
    }

    public $timestamps = false;
}
