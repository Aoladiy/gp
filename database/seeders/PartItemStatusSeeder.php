<?php

namespace Database\Seeders;

use App\Models\PartItemStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartItemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'В наличии'],
            ['name' => 'Списано'],
        ];

        foreach ($statuses as $status) {
            PartItemStatus::query()->create($status);
        }
    }
}
