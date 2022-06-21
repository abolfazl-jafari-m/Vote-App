<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            "body"=>$this->faker->realText,
            'user_id'=> $this->faker->numberBetween(1,19),
            'idea_id'=>$this->faker->numberBetween(1,30),
            'status_id'=>$this->faker->numberBetween(1,5)
        ];
    }
}
