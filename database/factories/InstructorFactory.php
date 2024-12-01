<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $department = DB::table('departments')->inRandomOrder()->first();
        return [
            'department_id' => $department->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            //Philippine Number Regex
            'contact_number' => $this->faker->regexify('^(09|\+639)\d{9}$'),
        ];
    }
}
