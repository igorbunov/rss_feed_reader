<?php

namespace Database\Factories;

use App\Models\FeedResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'feed_id' => rand(1, 6),
            'link' => $this->faker->url,
            'title' => $this->faker->text(50)
        ];
    }
}
