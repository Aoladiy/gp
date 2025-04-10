<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'name' => 'Admin',
            'email' => '1@1',
            'password' => Hash::make('123'),
        ]);

        $this->call([
            PartItemStatusSeeder::class,
            StockMovementTypeSeeder::class,
            RotationMethodsSeeder::class,
        ]);
    }
}
