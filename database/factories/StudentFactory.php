<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentYear = date('Y');
        $randomNumber = $currentYear. $this->faker->numerify('######');
        return [
            'student_number' => $randomNumber,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
