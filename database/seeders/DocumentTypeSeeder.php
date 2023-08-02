<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('dep_id' => 1, 'code' => '',  'desc' => 'เอกสารข้อมูลสำคัญ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 1, 'code' => '',  'desc' => 'เอกสารเกี่ยวกับคน', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 1, 'code' => '',  'desc' => 'เอกสารสำคัญ', 'parent' => 1, 'pattern' => 'SE[Y][m][4]', 'expire' => '3 years',),
            array('dep_id' => 1, 'code' => '',  'desc' => 'ข้อมูลผู้ถือหุ้น', 'parent' => 2, 'pattern' => 'SO[Y][4]', 'expire' => '3 years',),
            array('dep_id' => 1, 'code' => '',  'desc' => 'ข้อมูลบริหาร', 'parent' => 1, 'pattern' => 'ME[Y][4]', 'expire' => '3 years',),
            
            array('dep_id' => 2, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 2, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 6, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),
            
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารลูกค้า', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารผูรับเหมา', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'สำเนาบัตรประชาชน', 'parent' => 8, 'pattern' => 'CP[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารบริษัท', 'parent' => 8, 'pattern' => 'CO[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'สำเนาบัตรประชาชน', 'parent' => 9, 'pattern' => 'CP[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารบริษัท', 'parent' => 9, 'pattern' => 'CO[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 3, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 10, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),
            
            array('dep_id' => 4, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 4, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 16, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),
            
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารบริษัท', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารพนักงาน', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 18, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'สำเนาบัตรประชาชน', 'parent' => 19, 'pattern' => 'CP[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารสัญญา', 'parent' => 19, 'pattern' => 'CT[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'ใบสมัครงาน', 'parent' => 19, 'pattern' => 'AP[Y][m][4]', 'expire' => '1 years',),
            array('dep_id' => 5, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 20, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),
            
            array('dep_id' => 6, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => null, 'pattern' => '', 'expire' => '1 years',),
            array('dep_id' => 6, 'code' => '',  'desc' => 'เอกสารอื่นๆ', 'parent' => 26, 'pattern' => 'OT/[Y]-[m][3]', 'expire' => '1 years',),

        );

        foreach ($data as $value) {
            DB::table('document_types')->insert([
                'dep_id' => $value['dep_id'],
                'code' => $value['code'],
                'desc' => $value['desc'],
                'parent' => $value['parent'],
                'pattern' => $value['pattern'],
                'expire' => $value['expire'],
            ]);
        }
    }
}
