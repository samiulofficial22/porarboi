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

        User::updateOrCreate(
        ['email' => 'adminsamiul@gmail.com'],
        [
            'name' => 'Admin User',
            'password' => bcrypt('samiporarboi'),
            'role' => 'admin',
        ]
        );

        User::updateOrCreate(
        ['email' => 'user@gmail.com'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]
        );
    }
}
