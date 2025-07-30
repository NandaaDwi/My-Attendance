<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        $majors = DB::table('majors')->get();

        foreach ($majors as $major) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('classes')->insert([
                    'name' => $major->name . ' ' . $i,
                    'major_id' => $major->id,
                ]);
            }
        }
    }
}
