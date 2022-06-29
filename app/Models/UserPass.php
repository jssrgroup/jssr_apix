<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserPass extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, HasFactory;

    protected $table = 'tb_user_pass';
    protected $primaryKey = 'INDX';
    protected $connection = 'mysql3';

    protected $fillable = [
        'LOG_USER', 'E_MAIL', 'password', 'title', 'firstname', 'lastname',
    ];

    protected $hidden = [
        'LOG_PASSWORD','PROVINCE_ID', 'AMPHUR_ID', 'DISTRICT_ID',
        'SMSOK','TERRITORY','PIN_CODE','PIN_TEMP','LAST_GET_PINCODE','LAST_LOGIN','STATUS_LOGIN'
        ,'PAPER_LESS','LAST_LOGIN_TRANSPORT','MONEY_DETAIL','SUBSCRIBE_TRANSPORT'
        ,'BITLY','TEMP_SMS','FACEBOOK_ID','CODE_MOBILE_LOGIN','ADDRESS_TYPE'
        ,'BITLY_ONLINE','SMS_WARNING','BID_PROXY','BLACK_LIST_NEGO'
        ,'CURRENT_BID','SMS_NEGO','TIME_SMS_WARNING','PERCENT_WARNING','USER_WARNING','ANNOUN_WARNING'
        ,'ID_NO','STATUS_OPEN_ONLINE','POINT_RESERVE','POINT_USED','POINT_REMAIN','POINT_EXPIRE'
        ,'MEMBER_REMARK','MEMBER_TYPE','JURISTIC_TYPE','TIME_ACCEPT_PRIVACY','TEMP_LOOP','STATUS_MOVE'
        ,'VIP','TYPE_USER','JCD_LOGIN_COUNT','JCD_BLACKLIST','BLOCK_STATUS','MAX_BID_JCD','VALUE_ERP',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public $timestamps = false;
}
