<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $philippinePhoneNumber = '09' . fake()->numberBetween(10, 99) . '-' . fake()->numberBetween(100, 999) . '-' . fake()->numberBetween(1000, 9999);

        return [
            'instructor_username' => fake()->firstName(),
            'instructor_password' => '1234567890', 
            'instructor_fname' => fake()->firstName(),
            'instructor_lname' => fake()->lastName(),
            'instructor_bday' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'instructor_gender' => fake()->randomElement(['Male', 'Female']),
            'instructor_contactno' => $philippinePhoneNumber,
            'instructor_email' => fake()->safeEmail(),
            'status' => 'Pending',
            'instructor_security_code' => '111111',
            'instructor_credentials' => 'null',
        ];
    }
}
