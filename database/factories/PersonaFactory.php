<?php

namespace Database\Factories;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'CI' => $this->faker->unique()->numerify('########'),
            'Telefono' => $this->faker->phoneNumber(),
            'Direccion' => $this->faker->address(),
            'estadoRegistro' => 'Pendiente',
            'Estado_Registro' => 'Pendiente',
            'user_id' => User::factory(),
        ];
    }
}
