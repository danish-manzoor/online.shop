<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use App\Models\Product;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ───── Admin account ─────
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin_password'),
            'role' => 2,   // 2 = admin
        ]);

        // ───── Regular user account ─────
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('user_password'),
            'role' => 1,   // 1 = normal user
        ]);

        Category::factory(30)->create();
        Product::factory(10)->create();
        $this->call([
            CountrySeeder::class,
            BrandSeeder::class
        ]);
    }
}
