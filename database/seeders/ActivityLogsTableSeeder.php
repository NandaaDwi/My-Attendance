<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('activity_logs')->insert([
            ['id' => 1, 'user_id' => 1, 'activity' => 'Admin created initial data', 'created_at' => Carbon::now()],
            ['id' => 2, 'user_id' => 2, 'activity' => 'Staff updated attendance', 'created_at' => Carbon::now()],
        ]);
    }
}
