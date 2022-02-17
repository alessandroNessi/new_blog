<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subscriptions=[User::SUBSCRIPTION_FREE,User::SUBSCRIPTION_PREMIUM];
        return [
            'first_name' => $this->faker->firstName(),
            'first_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => password_hash('password',PASSWORD_DEFAULT),
            'api_token' => Str::random(64),
            'picture' => 'profile_pic.png',
            'subscription' => $subscriptions[array_rand($subscriptions)],
        ];
    }
}
