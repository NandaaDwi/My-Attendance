<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParentDetailsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('parent_details')->insert([
            ['id' => 1, 'user_id' => 5, 'full_name' => 'Mr. John Parent', 'occupation' => 'Engineer', 'relationship' => 'father', 'email' => 'parent1@example.com', 'phone' => '082233445566', 'address' => 'Jl. Melati No.3', 'student_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
