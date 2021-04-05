<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uid' => Uuid::uuid4(),
            'title' => $this->faker->realText($maxNbChars = 30, $indexSize = 2),
            'content' => $this->faker->text,
            'public' => rand(0,1),
            'user_id' => User::factory(),
            'created_at' => $this->faker->dateTime($max = 'now', $timezone = null),
        ];
    }
}
