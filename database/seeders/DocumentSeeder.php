<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('ref_id' => 71, 'ref_dep_id' => 2, 'ref_doc_type_id' => 2, 'ref_user_id' => 71, 'image_name' => 'EMPXXX1', 'file_name' => 'EMPXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 38, 'ref_dep_id' => 2, 'ref_doc_type_id' => 3, 'ref_user_id' => 71, 'image_name' => 'CUSXXX1', 'file_name' => 'CUSXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 2, 'ref_doc_type_id' => 4, 'ref_user_id' => 71, 'image_name' => 'OTHXXX1', 'file_name' => 'OTHXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 2, 'ref_doc_type_id' => 1, 'ref_user_id' => 71, 'image_name' => 'COMXXX1', 'file_name' => 'COMXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 2, 'ref_doc_type_id' => 1, 'ref_user_id' => 71, 'image_name' => 'COMXXX2', 'file_name' => 'COMXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 44, 'ref_dep_id' => 2, 'ref_doc_type_id' => 3, 'ref_user_id' => 71, 'image_name' => 'CUSXXX2', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 49, 'ref_dep_id' => 2, 'ref_doc_type_id' => 3, 'ref_user_id' => 71, 'image_name' => 'CUSXXX3', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),

            array('ref_id' => 111, 'ref_dep_id' => 1, 'ref_doc_type_id' => 2, 'ref_user_id' => 111, 'image_name' => 'EMPXXX1', 'file_name' => 'EMPXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 69, 'ref_dep_id' => 1, 'ref_doc_type_id' => 3, 'ref_user_id' => 111, 'image_name' => 'CUSXXX1', 'file_name' => 'CUSXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 1, 'ref_doc_type_id' => 4, 'ref_user_id' => 111, 'image_name' => 'OTHXXX1', 'file_name' => 'OTHXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 1, 'ref_doc_type_id' => 1, 'ref_user_id' => 111, 'image_name' => 'COMXXX1', 'file_name' => 'COMXXX1.jpg', 'expire_date_at' => '2023-07-31',),

            array('ref_id' => 7, 'ref_dep_id' => 4, 'ref_doc_type_id' => 2, 'ref_user_id' => 7, 'image_name' => 'EMPXXX1', 'file_name' => 'EMPXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 69, 'ref_dep_id' => 4, 'ref_doc_type_id' => 3, 'ref_user_id' => 7, 'image_name' => 'CUSXXX1', 'file_name' => 'CUSXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 78, 'ref_dep_id' => 4, 'ref_doc_type_id' => 3, 'ref_user_id' => 7, 'image_name' => 'CUSXXX2', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 79, 'ref_dep_id' => 4, 'ref_doc_type_id' => 3, 'ref_user_id' => 7, 'image_name' => 'CUSXXX3', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 86, 'ref_dep_id' => 4, 'ref_doc_type_id' => 3, 'ref_user_id' => 7, 'image_name' => 'CUSXXX4', 'file_name' => 'CUSXXX4.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 4, 'ref_doc_type_id' => 4, 'ref_user_id' => 7, 'image_name' => 'OTHXXX1', 'file_name' => 'OTHXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 4, 'ref_doc_type_id' => 1, 'ref_user_id' => 7, 'image_name' => 'COMXXX1', 'file_name' => 'COMXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 4, 'ref_doc_type_id' => 1, 'ref_user_id' => 7, 'image_name' => 'COMXXX2', 'file_name' => 'COMXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 4, 'ref_doc_type_id' => 1, 'ref_user_id' => 7, 'image_name' => 'COMXXX3', 'file_name' => 'COMXXX3.jpg', 'expire_date_at' => '2023-07-31',),

            array('ref_id' => 115, 'ref_dep_id' => 5, 'ref_doc_type_id' => 2, 'ref_user_id' => 115, 'image_name' => 'EMPXXX1', 'file_name' => 'EMPXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 102, 'ref_dep_id' => 5, 'ref_doc_type_id' => 3, 'ref_user_id' => 115, 'image_name' => 'CUSXXX1', 'file_name' => 'CUSXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 5, 'ref_doc_type_id' => 1, 'ref_user_id' => 115, 'image_name' => 'COMXXX1', 'file_name' => 'COMXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 5, 'ref_doc_type_id' => 4, 'ref_user_id' => 115, 'image_name' => 'OTHXXX1', 'file_name' => 'OTHXXX1.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 106, 'ref_dep_id' => 5, 'ref_doc_type_id' => 3, 'ref_user_id' => 115, 'image_name' => 'CUSXXX2', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 0, 'ref_dep_id' => 5, 'ref_doc_type_id' => 1, 'ref_user_id' => 115, 'image_name' => 'COMXXX2', 'file_name' => 'COMXXX2.jpg', 'expire_date_at' => '2023-07-31',),
            array('ref_id' => 111, 'ref_dep_id' => 5, 'ref_doc_type_id' => 3, 'ref_user_id' => 115, 'image_name' => 'CUSXXX3', 'file_name' => 'CUSXXX2.jpg', 'expire_date_at' => '2023-07-31',),
        );

        foreach ($data as $value) {
            DB::table('documents')->insert([
                'ref_id' => $value['ref_id'],
                'ref_dep_id' => $value['ref_dep_id'],
                'ref_doc_type_id' => $value['ref_doc_type_id'],
                'ref_user_id' => $value['ref_user_id'],
                'image_name' => $value['image_name'],
                'file_name' => $value['file_name'],
                'expire_date_at' => $value['expire_date_at'],
            ]);
        }
    }
}
