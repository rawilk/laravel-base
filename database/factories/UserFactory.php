<?php

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Features;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $definition = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'secret',
            'timezone' => $this->faker->timezone,
            'remember_token' => Str::random(10),
        ];

        if (Features::enabled(Features::emailVerification())) {
            $definition['email_verified_at'] = now();
        }

        return $definition;
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
