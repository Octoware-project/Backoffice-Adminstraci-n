<?php

namespace Database\Factories;

use App\Models\UserAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserAdminFactory extends Factory
{
    protected $model = UserAdmin::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}
