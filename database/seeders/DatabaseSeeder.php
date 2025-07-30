<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            MajorsTableSeeder::class,
            ClassesTableSeeder::class,
            UsersTableSeeder::class,
            AcademicYearsTableSeeder::class,
            StudentDetailsTableSeeder::class,
            EmployeeDetailsTableSeeder::class,
            ParentDetailsTableSeeder::class,
            AbsenceReasonsTableSeeder::class,
            HolidaysTableSeeder::class,
            AttendancesTableSeeder::class,
            ActivityLogsTableSeeder::class,
            AttendanceHistoriesTableSeeder::class,
            NotificationsTableSeeder::class,
        ]);
    }
}
