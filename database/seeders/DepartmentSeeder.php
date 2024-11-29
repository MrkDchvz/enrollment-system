<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['department_code' => "BSCS", 'department_name' => 'Department of Computer Studies'],
            ['department_code' => "BSIT", 'department_name' => 'Department of Information Technology'],
        ]);
    }
}
