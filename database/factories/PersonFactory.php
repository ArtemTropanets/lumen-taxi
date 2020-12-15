<?php

namespace Database\Factories;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = Carbon::now();
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'default_address' => $this->faker->streetAddress,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
