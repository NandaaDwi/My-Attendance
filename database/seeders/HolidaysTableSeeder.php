<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidaysTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('holidays')->insert([
            ['id' => 1, 'date' => '2025-01-01', 'name' => 'New Year', 'type' => 'national_holiday', 'academic_year_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'date' => '2025-05-01', 'name' => 'Labor Day', 'type' => 'national_holiday', 'academic_year_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'date' => '2025-12-25', 'name' => 'Christmas', 'type' => 'national_holiday', 'academic_year_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
