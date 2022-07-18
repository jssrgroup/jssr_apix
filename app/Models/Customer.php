<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'data',
        'dataaccept',
        'password',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        // 'birthday' => 'date:Y-m-d',
        'updated_at' => 'datetime:d/m/Y H:00',
    ];

    protected $appends = ['personal_data', 'personal_accept_consent', 'update'];

    public function getPersonalDataAttribute()
    {
        $decrypt = json_decode(jdecrypt($this->attributes['data']), true);
        // return $this->attributes['data'];
        $acceptConsent = json_decode(jdecrypt($this->attributes['dataaccept']), true);
        $personalData = DB::table('personal_data')
            // ->where('necessary', true)
            ->orderBy('order_by', 'asc')
            ->select('code', 'necessary')
            ->get();
        $personals = [];
        foreach ($personalData as $value) {
            // if (array_key_exists($value->code, $decrypt))
            $personals[$value->code] = $value->necessary ? true : false;
        }
        if (!$acceptConsent)
            $acceptConsent = $personals;

        // return $acceptConsent;
        // return $personals;
        $acceptConsent = array_merge($personals, $acceptConsent);
        // return array_unique(array_merge($acceptConsent, $personals));
        try {
            $data = [];
            foreach ($acceptConsent as $key => $value) {
                if (array_key_exists($key, $decrypt))
                    if ($value)
                        $data[$key] = $decrypt[$key];
                // $data[$key] = $decrypt;
            }
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $decrypt;
        }
    }

    public function getPersonalAcceptConsentAttribute()
    {
        $acceptConsent = json_decode(jdecrypt($this->attributes['dataaccept'], true));
        if (!$acceptConsent) {
            $personals = DB::table('personal_data')
                // ->where('necessary', true)
                ->orderBy('order_by', 'asc')
                ->select('code', 'necessary')
                ->get();
            $acceptConsent = [];
            foreach ($personals as $value) {
                // if (array_key_exists($value->code, $decrypt))
                $acceptConsent[$value->code] = $value->necessary ? true : false;
            }
        }
        return $acceptConsent;
    }

    public function getUpdateAttribute()
    {
        return $this->attributes['updated_at'];
    }
}
