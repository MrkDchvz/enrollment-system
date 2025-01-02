<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fees')->insert([
            ['name' => "Com. Lab", 'amount' => 800.00],
            ['name' => "NSTP", 'amount' => 300.00],
            ['name' => "Reg. Fee", 'amount' => 55.00],
            ['name' => "ID", 'amount' => 30.00],
            ['name' => "Late Reg.", 'amount' => 40.00],
            ['name' => "Insurance", 'amount' => 25.00],
            ['name' => "Tuition Fee", 'amount' => 3200.00],
            ['name' => "SFDF", 'amount' => 1500.00],
            ['name' => "SRF", 'amount' => 2025.00],
            ['name' => "Misc.", 'amount' => 432.00],
            ['name' => "Athletics", 'amount' => 100.00],
            ['name' => "SCUAA", 'amount' => 100.00],
            ['name' => "Library Fee", 'amount' => 50.00],
            ['name' => "Lab Fees", 'amount' => 800.00],
            ['name' => "Other Fees", 'amount' => 80.00],
        ]);
    }
}
