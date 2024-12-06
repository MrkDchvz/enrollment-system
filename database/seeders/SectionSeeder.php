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
        $departmentId = Department::where('department_code', 'BSCS')->first();
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        DB::table('sections')->insert([
            'department_id' => $departmentId->id,
            'school_year' => "{$currentYear}-{$nextYear}",
            'class_number' => 1,
            'year_level' => 3,
        ]);
    }
}
