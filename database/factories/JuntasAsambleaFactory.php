<?php

namespace Database\Factories;

use App\Models\JuntasAsamblea;
use Illuminate\Database\Eloquent\Factories\Factory;

class JuntasAsambleaFactory extends Factory
{
    protected $model = JuntasAsamblea::class;

    public function definition()
    {
        return [
            'lugar' => $this->faker->city(),
            'fecha' => $this->faker->date(),
            'detalle' => $this->faker->sentence(),
        ];
    }
}
