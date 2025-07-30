<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeDetailsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('employee_details')->insert([
            ['id' => 1, 'user_id' => 3, 'nip' => 'NIP001', 'gender' => 'F', 'place_of_birth' => 'Bandung', 'date_of_birth' => '1980-03-15', 'religion' => 'Christianity', 'address' => 'Jl. Sudirman No.10', 'phone' => '089876543210', 'class_id' => 1, 'photo' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'user_id' => 2, 'nip' => 'NIP002', 'gender' => 'M', 'place_of_birth' => 'Surabaya', 'date_of_birth' => '1975-12-20', 'religion' => 'Hinduism', 'address' => 'Jl. Gatot Subroto No.5', 'phone' => '087654321098', 'class_id' => null, 'photo' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'user_id' => 1, 'nip' => 'NIP003', 'gender' => 'F', 'place_of_birth' => 'Jakarta', 'date_of_birth' => '1975-12-20', 'religion' => 'Islam', 'address' => 'Jl. Asia Afrika No.5', 'phone' => '0876543210912', 'class_id' => null, 'photo' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
    