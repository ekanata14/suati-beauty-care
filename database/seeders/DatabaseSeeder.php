<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HomeContent;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        HomeContent::create([
            'title' => 'SUATI BEUATY CARE',
            'description' => 'Welcome to Suati Beauty Care, your one-stop destination for all your beauty needs. We offer a wide range of services and products to help you look and feel your best.',
            'logo' => 'logo.jpeg', // Assuming you have a logo file in the public directory
        ]);
    }
}
