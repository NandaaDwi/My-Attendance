<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicYearsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('academic_years')->insert([
            ['id' => 1, 'name' => '2024/2025', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => '2023/2024', 'active' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
