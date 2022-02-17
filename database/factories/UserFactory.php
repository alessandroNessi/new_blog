<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            //Hash::verify();
            'api_token' => Str::random(64),
            'picture' => 'profile_pic.png',
            'subscription' => $subscriptions[array_rand($subscriptions)],
        ];
    }
}
