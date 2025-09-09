<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'avatar' => 'none',
            'name' => 'putraservice',
            'email' => 'pianseptiana@yahoo.com',
            'password' => Hash::make('pian3073'),
            'is_admin' => true,
        ]);

        Category::create([
            'name' => 'service-ac'
        ]);
    
        Category::create([
            'name' => 'pembersihan-ac'
        ]);
    
        Category::create([
            'name' => 'bongkar-pasang-ac'
        ]);
    }
}
