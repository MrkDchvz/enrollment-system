<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $schoolYear = static::getCurrentSchoolYear();

        try {
            // Attempt to fetch a random section
            $section = Section::inRandomOrder()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // If no section exists, create a new one
            $CS_Department = Department::create([
                'department_code' => 'BSCS',
                'department_name' => 'Department of Computer Studies'
            ]);
            $IT_Department = Department::create([
                'department_code' => 'BSIT',
                'department_name' => 'Department of Information Technology'
            ]);
            $randomDepartment = Department::inRandomOrder()->firstOrFail();

            $section = Section::create([
                'department_id' => $randomDepartment->id,
                'year_level' => $this->faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
                'class_number' => $this->faker->numberBetween(1, 10),
            ]);
        }
        // This ensures that the department, yearlevel, schoolYear is the same with the section
        $department = $section->department;
        $yearLevel = $section->year_level;

        try {
            $user = User::role('Admin')->inRandomOrder()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $user = User::factory()->create();
            $user->assignRole('Admin');
        }

        $scholarship = "CHED Free Tution and Misc. Fee";
        return [
            'student_id' => function () {
                return Student::factory()->create()->id;
            },
            'department_id' => $department->id,
//            NOTE: CHANGE THIS TO REGISTRAR'S ID.
            'user_id' =>  $user->id,
            'section_id' => $section->id,
            'scholarship' => $scholarship,
            'registration_status' => $this->faker->randomElement(['REGULAR', 'IRREGULAR']),
            'school_year' => $schoolYear,
            'student_type' => $this->faker->randomElement(['Regular', 'Irregular', 'Transferee', 'New']),
            'year_level' => $yearLevel,
            'semester' => $this->faker->randomElement(['1st Semester', '2nd Semester']),
            'old_new_student' => $this->faker->randomElement(['Old Student', 'New Student']),
            'enrollment_date' => Carbon::now()->format('Y-m-d'),
        ];
    }
    public static function getCurrentSchoolYear() : string {
        // Current Date
        $date = Carbon::now();
        // Current Year $ Month
        $year = $date->year;
        $month = $date->month;
        // Set a new school year if the enrollment is in around august
        // If the year is 2024 and the student enrolled around august 2024
        // Then the school year will be 2024 - 2024
        if ($month >= 8) {
            $startYear = $date->year;
            $endYear = $date->year + 1;
        }
        // Retain the current school year if the enrollment is around february
        // If the year is 2024 and the student enrolled around february 2024
        // Then the school year is 2023-2024
        else {
            $startYear = $date->year - 1;
            $endYear = $date->year;
        }
        return trim(
            sprintf('%s-%s', $startYear, $endYear)
        );
    }
}
