<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorsTableSeeder extends Seeder
{
    public function run()
    {
        $majors = ['RPL', 'BP', 'TJKT', 'Mesin', 'Otomotif', 'Tekstil', 'Elektro'];

        foreach ($majors as $major) {
            DB::table('majors')->insert([
                'name' => $major,
            ]); 
        }
    }
}
