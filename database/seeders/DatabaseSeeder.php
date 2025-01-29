<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insert roles
        \DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Expert'],
            ['id' => 3, 'name' => 'Pet Owner'],
            ['id' => 4, 'name' => 'Auditor'],
            ['id' => 5, 'name' => 'Content Creator'],
        ]);
        
        // \App\Models\User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin Kārlis',
            'email' => 'kaarlisbrakis@gmail.com',
            'password' => '12345678' // Hashed by UserFactory
        ]);
        // Attach admin role (will not work without existing roles)
        $user->roles()->attach(Role::find(1));

        $user = User::factory()->create([
            'name' => 'User Kārlis',
            'email' => 'ip20.k.brakis@gmail.com',
            'password' => '12345678' // Hashed by UserFactory
        ]);
    }
}
