<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TodoList;

class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $list_id = TodoList::inRandomOrder()->first();
        return [
            'name' => $this->faker->text(24),
            'list_id' => $list_id->id,
            'completed' => $this->faker->randomElement([0,1]),
            'dueDate' => $this->faker->dateTimeBetween('-1 week', '+1 week')
        ];
    }
}
