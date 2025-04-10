<?php

namespace Database\Seeders;

use App\Models\StockMovementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockMovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Поступление'],
            ['name' => 'Списание'],
            ['name' => 'Перемещение'],
        ];

        foreach ($types as $type) {
            StockMovementType::query()->create($type);
        }
    }
}
