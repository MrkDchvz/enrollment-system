<?php

namespace Database\Factories;

use App\Models\Student;
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
        $department = DB::table('departments')->inRandomOrder()->first();
        $user = DB::table('users')->inRandomOrder()->first();
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
            'user_id' =>  $user->id,
            'scholarship' => $scholarship,
            'registration_status' => $this->faker->randomElement(['REGULAR', 'IRREGULAR']),
            'semester' => $this->faker->randomElement(['1st Semester', '2nd Semester']),
            'year_level' => $this->faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
            'school_year' => "$currentYear-$nextYear",
            'old_new_student' => $this->faker->randomElement(['Old Student', 'New Student']),
            'enrollment_date' => Carbon::now()->format('Y-m-d'),
        ];
    }
}
