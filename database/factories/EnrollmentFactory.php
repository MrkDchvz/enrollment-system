<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
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
        $department = Department::inRandomOrder()->firstOrFail();
        // in_array ensures that the value will be either BSCS or BSIT
        if (in_array($department->department_code, ['BSCS', 'BSIT'])) {
            $section = DB::table('sections')
                ->where('department_code', $department->department_code)
                ->inRandomOrder()
                ->first();
        }

        $section = DB::table('sections')->inRandomOrder()->first();
        $user = User::role('Officer')->inRandomOrder()->first();
//        Carbon is a dateTime Library built in laravel
//        now() = gets the current date, ->year =  gets the year from a date
//        addYear() = a method to add year to a date
        $currentYear = Carbon::now()->year;
        $nextYear = Carbon::now()->addYear()->year;
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
            'semester' => $this->faker->randomElement(['1st Semester', '2nd Semester']),
            'old_new_student' => $this->faker->randomElement(['Old Student', 'New Student']),
            'enrollment_date' => Carbon::now()->format('Y-m-d'),
        ];
    }
}
