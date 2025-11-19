<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAdmin;
use Illuminate\Support\Facades\Hash;
class UserAdminSeeder extends Seeder
{
    public function run(): void
    {
        UserAdmin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'), // hash seguro
        ]);
    }
}
