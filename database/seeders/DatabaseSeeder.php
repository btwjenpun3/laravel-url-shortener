<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Setting::factory(1)->create();

        \App\Models\Role::create([
            'name' => 'admin'
        ]);

        \App\Models\Role::create([
            'name' => 'member'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'),
            'role_id' => 1,
            'status' => 1
        ]);
    }
}
