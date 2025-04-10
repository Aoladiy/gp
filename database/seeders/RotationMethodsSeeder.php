<?php

namespace Database\Seeders;

use App\Models\RotationMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RotationMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'FIFO',  'description' => 'First In, First Out'],
            ['name' => 'FEFO',  'description' => 'First Expired, First Out'],
        ];

        foreach ($methods as $method) {
            RotationMethod::query()->create($method);
        }
    }
}
