<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('code'=>'code','name'=>'รหัส','necessary'=>false),
            array('code'=>'firstname','name'=>'ชื่อ','necessary'=>true),
            array('code'=>'lastname','name'=>'นามสกุล','necessary'=>true),
            array('code'=>'email','name'=>'อีเมล์','necessary'=>false),
            array('code'=>'mobile','name'=>'มือถือ','necessary'=>true),
            array('code'=>'address','name'=>'ที่อยู่','necessary'=>false),
            array('code'=>'district','name'=>'ตำบล','necessary'=>false),
            array('code'=>'amphur','name'=>'อำเภอ','necessary'=>false),
            array('code'=>'province','name'=>'จังหวัด','necessary'=>false),
            array('code'=>'zipcode','name'=>'รหัสไปรษณีย์','necessary'=>false),
            array('code'=>'dateofbirth','name'=>'วันเกิด','necessary'=>false),
            array('code'=>'race','name'=>'เชื้อชาติ','necessary'=>false),
            array('code'=>'nationality','name'=>'สัญชาติ','necessary'=>false),
            array('code'=>'religion','name'=>'ศาสนา','necessary'=>false),
            array('code'=>'identitycardno','name'=>'เลขบัตรที่บัตรประชาชน','necessary'=>false),
            array('code'=>'expirationdate','name'=>'วันที่หมดอายุ','necessary'=>false),
            array('code'=>'gender','name'=>'เพศ','necessary'=>false),
        );

        foreach ($data as $value) {
            DB::table('personal_data')->insert([
                'code' => $value['code'],
                'name' => $value['name'],
                'necessary' => $value['necessary'],
            ]);
        }
    }
}
