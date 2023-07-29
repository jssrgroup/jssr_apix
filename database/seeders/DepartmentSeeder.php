<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('code'=>'IA','desc'=>'IA',),
            array('code'=>'AM','desc'=>'AM',),
            array('code'=>'ACC','desc'=>'บัญชี',),
            array('code'=>'LAW','desc'=>'กฎหมาย',),
            array('code'=>'HR','desc'=>'HR',),
            array('code'=>'IT','desc'=>'Admin',),
        );

        foreach ($data as $value) {
            DB::table('departments')->insert([
                'code' => $value['code'],
                'desc' => $value['desc'],
            ]);
        }
    }
}
