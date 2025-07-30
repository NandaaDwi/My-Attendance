<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('notifications')->insert([
            ['id' => 1, 'parent_id' => 1, 'attendance_id' => 2, 'type' => 'sms', 'status' => 'sent', 'sent_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['id' => 2, 'parent_id' => 1, 'attendance_id' => 1, 'type' => 'email', 'status' => 'pending', 'sent_at' => null, 'created_at' => Carbon::now()],
        ]);
    }
}
