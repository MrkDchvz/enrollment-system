<?php

namespace Database\Seeders;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalYearLevel = 4;
        $totalClassNumbers = 7;

        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        $schoolYear =  "{$currentYear}-{$nextYear}";

        $CS_Department = Department::where('department_code', 'BSCS')->first();
        $IT_Department = Department::where('department_code', 'BSIT')->first();
        for ($i = 1; $i <= $totalYearLevel; $i++) {
            $yearLevel = match ($i) {
                1 => "1st Year",
                2 => "2nd Year",
                3 => "3rd Year",
                4 => "4th Year",
                default => "Unknown Year",
            };
            for ($j = 1; $j <= $totalClassNumbers; $j++) {
                DB::table('sections')->insert([
                    'department_id' => $CS_Department->id,
                    'school_year' => $schoolYear,
                    'class_number' => $j,
                    'year_level' => $yearLevel,
                    ]);

                DB::table('sections')->insert([
                    'department_id' => $IT_Department->id,
                    'school_year' => $schoolYear,
                    'class_number' => $j,
                    'year_level' => $yearLevel,
                ]);

                DB::table('sections')->insert([
                    'department_id' => $CS_Department->id,
                    'school_year' =>  "2023-2024",
                    'class_number' => $j,
                    'year_level' => $yearLevel,
                ]);

                DB::table('sections')->insert([
                    'department_id' => $IT_Department->id,
                    'school_year' => "2023-2024",
                    'class_number' => $j,
                    'year_level' => $yearLevel,
                ]);
            }
        }


    }
}
