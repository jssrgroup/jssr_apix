<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('user_id' => 71, 'dep_id' => 2, 'role_id' => 3, 'status' => 1, 'is_delete' => 0,),
            array('user_id' => 146, 'dep_id' => 6, 'role_id' => 1, 'status' => 1, 'is_delete' => 0,),
            array('user_id' => 7, 'dep_id' => 4, 'role_id' => 2, 'status' => 1, 'is_delete' => 0,),
            array('user_id' => 111, 'dep_id' => 1, 'role_id' => 3, 'status' => 1, 'is_delete' => 0,),
            array('user_id' => 18, 'dep_id' => 3, 'role_id' => 3, 'status' => 1, 'is_delete' => 0,),
            array('user_id' => 115, 'dep_id' => 5, 'role_id' => 2, 'status' => 1, 'is_delete' => 0,),
        );

        foreach ($data as $value) {
            DB::table('user_management')->insert([
                'user_id' => $value['user_id'],
                'dep_id' => $value['dep_id'],
                'role_id' => $value['role_id'],
                'status' => $value['status'],
                'is_delete' => $value['is_delete'],
            ]);
        }
    }
}
