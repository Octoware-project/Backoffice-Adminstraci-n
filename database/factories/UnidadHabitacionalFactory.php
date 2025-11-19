<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnidadHabitacional>
 */
class UnidadHabitacionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;
        $piso = fake()->numberBetween(1, 10);
        $numero = chr(65 + fake()->numberBetween(0, 2)) . '-' . str_pad($counter++, 3, '0', STR_PAD_LEFT);
        
        return [
            'numero_departamento' => $numero,
            'piso' => $piso,
            'estado' => fake()->randomElement(['En construccion', 'Finalizado']),
        ];
    }

    /**
     * Indicate that the unit is finished.
     */
    public function finalizado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Finalizado',
        ]);
    }

    /**
     * Indicate that the unit is under construction.
     */
    public function enConstruccion(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'En construccion',
        ]);
    }
}
