<?php

namespace Database\Seeders;

use App\Models\StorageLocation;
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
        User::factory()->create([
            'name' => 'StorageManager',
            'email' => 'storageManager@storageManager',
            'password' => Hash::make('123'),
        ]);
        User::factory()->create([
            'name' => 'ReportUser',
            'email' => 'reportUser@reportUser',
            'password' => Hash::make('123'),
        ]);

        $this->call([
            PartItemStatusSeeder::class,
            StockMovementTypeSeeder::class,
            RotationMethodSeeder::class,
            StorageLocationSeeder::class,
            PartTemplateSeeder::class,
            PartSeeder::class,
            PartBatchSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
