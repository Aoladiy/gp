<?php

namespace Database\Seeders;

use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class StorageLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $main = StorageLocation::query()
            ->updateOrCreate(
                ['name' => 'Главный склад'],
                ['parent_id' => null]
            );

        $sub1 = StorageLocation::query()
            ->updateOrCreate(
                ['name' => 'Склад №1 (внутри главного)'],
                ['parent_id' => $main->id]
            );

        $sub2 = StorageLocation::query()
            ->updateOrCreate(
                ['name' => 'Склад №2 (внутри главного)'],
                ['parent_id' => $main->id]
            );

        $external = StorageLocation::query()
            ->updateOrCreate(
                ['name' => 'Внешний склад'],
                ['parent_id' => null]
            );

        $cold = StorageLocation::query()
            ->updateOrCreate(
                ['name' => 'Холодильная камера'],
                ['parent_id' => $external->id]
            );
    }
}
