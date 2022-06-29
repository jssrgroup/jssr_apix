<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Member extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, HasFactory;

    protected $table = 'tb_member';
    protected $primaryKey = 'INDX';
    protected $connection = 'mysql2';

    protected $fillable = [];

    protected $guarded = [];

    protected $hidden = [
        'BIDDER_NO', 'BIDDER_NO_LIVE', 'BIDDER_BO_ONSITE', 'BIDDER_NO_WEBBID', 'BIDDER_NO_TENDER',
        'BIDDER_NO_STATUS', 'DEPOSIT', 'MEBER_TYPE_ID', 'PASSWORD', 'INDUSTRY_TYPE', 'ADDRESS2',
        'ADDRESS3', 'CITY', 'PROVINCE_ID', 'PROVINCE_NAME', 'ZIP_CODE', 'COUNTRY_ID',
        'NEWS_BY_SMS', 'NEWS_BY_POST', 'NEWS_BY_EMAIL', 'PIN_CODE', 'PIN_TEMP', 'MAX_BID',
        'ACCOUNT_NO', 'ACCOUNT_NAME', 'BANK_ID', 'BANK', 'BRANCH', 'SWIFT_CODE', 'BID_POINT',
        'CONNECT_LEVEL', 'MOBILE_ACTIVE', 'MOBILE_ACTIVE_STAMP', 'ACTIVATE_KEY', 'ACTIVATE_TIME',
        'LOGIN_COUNT', 'LAST_LOG', 'ACCOUNT_STATUS', 'TOUR',
        'STATUS_LB', 'STATUS_CH', 'STATUS_GD', 'TRANSFER', 'CHECK_STATUS',
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
