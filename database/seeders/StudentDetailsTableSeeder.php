<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StudentDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $classes = DB::table('classes')->get();
        $userCounter = 1;

        foreach ($classes as $class) {
            for ($i = 1; $i <= 5; $i++) {
                $name = $faker->name;
                $email = 'student' . $userCounter . '@school.test';

                $userId = DB::table('users')->insertGetId([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('student_details')->insert([
                    'user_id' => $userId,
                    'nis' => 'NIS' . str_pad($userCounter, 5, '0', STR_PAD_LEFT),
                    'nisn' => 'NISN' . str_pad($userCounter, 5, '0', STR_PAD_LEFT),
                    'gender' => $faker->randomElement(['M', 'F']),
                    'place_of_birth' => $faker->city,
                    'date_of_birth' => $faker->date('Y-m-d', '2010-12-31'),
                    'religion' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'address' => $faker->address,
                    'phone' => $faker->unique()->phoneNumber,
                    'class_id' => $class->id,
                    'status' => 'active',
                    'photo' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $userCounter++;
            }
        }
    }
}
