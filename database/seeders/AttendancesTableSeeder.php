<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attendances')->insert([
            ['id' => 1, 'student_id' => 1, 'date' => '2025-07-01', 'status' => 'Present', 'reason_id' => null, 'note' => null, 'officer_id' => 2, 'academic_year_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'student_id' => 1, 'date' => '2025-07-02', 'status' => 'Sick', 'reason_id' => 1, 'note' => 'Flu', 'officer_id' => 2, 'academic_year_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
