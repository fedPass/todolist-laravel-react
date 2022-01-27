<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TodoListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
            'name' => $this->faker->text(32),
            'user_id' => $user->id
        ];
    }
}
