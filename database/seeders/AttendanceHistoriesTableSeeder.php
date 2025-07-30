<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceHistoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attendance_histories')->insert([
            ['id' => 1, 'attendance_id' => 2, 'user_id' => 2, 'change' => 'Changed status from Absent to Sick', 'created_at' => Carbon::now()],
        ]);
    }
}
