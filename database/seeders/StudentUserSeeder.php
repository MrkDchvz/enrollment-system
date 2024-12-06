<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::factory()->create();


        $studentId = $student->id;

        Enrollment::factory()->create([
            'student_id' => $studentId,
        ]);



    }
}
