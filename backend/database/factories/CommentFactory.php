<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => $this->faker->uuid(),
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->numberBetween(1000000000, 9999999999),
            'name' => $this->faker->name,
            'comment' => $this->faker->paragraph,
        ];
    }
}