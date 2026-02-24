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
            SettingSeeder::class ,
            CategorySeeder::class ,
            BookSeeder::class ,
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'adminsamiul@gmail.com',
            'password' => bcrypt('samiporarboi'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}
