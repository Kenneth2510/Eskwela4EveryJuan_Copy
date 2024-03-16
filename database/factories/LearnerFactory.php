<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Learner>
 */
class LearnerFactory extends Factory
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
            'learner_username' => fake()->firstName(),
            'learner_password' => '1234567890',
            'learner_security_code' => fake()->randomNumber(6),
            'learner_fname' => fake()->firstName(),
            'learner_lname' => fake()->lastName(),
            'learner_bday' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'learner_gender' => fake()->randomElement(['Male', 'Female']),
            'learner_contactno' => $philippinePhoneNumber,
            'learner_email' => fake()->unique()->safeEmail(),
            // 'status' => 'Pending',
        ];
    }
    
}
