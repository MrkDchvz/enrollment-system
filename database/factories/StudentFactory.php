<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $middleName = $this->faker->lastName();
        $email = $this->faker->unique()->safeEmail();
        $student = User::factory()->create([
            'name' => "{$firstName} {$middleName} {$lastName}",
            'email' => $email,
            'password' => Hash::make('student')
        ]);

        $student->assignRole('Student');
        return [
            'student_number' => $this->faker->regexify('^20\d{7}$'),
            'user_id' => $student->id,
            'first_name' => strtoupper($firstName),
            'last_name' => strtoupper($lastName),
            'middle_name' => strtoupper($middleName),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->address(),
            //Philippine Number Regex
            'contact_number' => $this->faker->regexify('^(09|\+639)\d{9}$'),
        ];
    }
}
