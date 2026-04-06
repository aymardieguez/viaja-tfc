<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            "name" => "Admin Pruebas",
            "email" => "admin@viaja.com",
            "password" => bcrypt("password"),
            "role_id" => 1,
        ]);

        User::factory()->create([
            "name" => "Usuario Pruebas",
            "email" => "usuario@viaja.com",
            "password" => bcrypt("password"),
            "role_id" => 2,
        ]);
    }
}
