<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         $admin = \App\Models\User::factory()->create([
             'first_name' => 'Mugy',
             'role' => 'admin',
             'email' => 'admin@email.com',
         ]);
        \App\Models\User::factory(20)->create();
    }
}
