<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('123'), 'role' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Staff', 'email' => 'staff@gmail.com', 'password' => Hash::make('123'), 'role' => 'staff', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Wali Kelas', 'email' => 'wali@gmail.com', 'password' => Hash::make('123'), 'role' => 'homeroom_teacher', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Siswa', 'email' => 'siswa@gmail.com', 'password' => Hash::make('123'), 'role' => 'student', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'name' => 'Orang Tua', 'email' => 'orangtua@gmail.com', 'password' => Hash::make('123'), 'role' => 'parent_student', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
