<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenceReasonsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('absence_reasons')->insert([
            ['id' => 1, 'name' => 'Sick'],
            ['id' => 2, 'name' => 'Family Emergency'],
            ['id' => 3, 'name' => 'Official Trip'],
        ]);
    }
}
