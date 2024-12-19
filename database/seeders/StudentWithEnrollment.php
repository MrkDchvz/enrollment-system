<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Uri\Encoder;
use Spatie\Permission\Models\Role;

class StudentWithEnrollment extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::factory()->create();
        $encoder = User::role('Admin')->first();

        Role::firstOrCreate(
            ['name' => 'Student'],
            ['guard_name' => 'web']
        );

        $student->user->assignRole('Student');

        $studentId = $student->id;

        Enrollment::factory()->create([
            'student_id' => $studentId,
            'user_id' => $encoder->id,
        ]);
    }
}
