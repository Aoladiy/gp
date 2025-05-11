<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\PartBatch;
use App\Models\PartItem;
use App\Models\PartItemStatus;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class PartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = PartItemStatus::query()
            ->firstWhere('name', 'В наличии');
        $part = Part::query()
            ->where('article_number', 'W9142')->first();
        $location = StorageLocation::query()
            ->where('name', 'Главный склад')->first();

        $batch = PartBatch::query()
            ->firstOrCreate([
                'batch_number' => 'BATCH-2025-01',
            ], [
                'production_date' => now()->subMonths(2),
                'expiration_date' => now()->addYears(2),
            ]);

        PartItem::query()
            ->updateOrCreate([
                'serial_number' => 'W9142-001',
            ], [
                'part_id' => $part->id,
                'part_batch_id' => $batch->id,
                'storage_location_id' => $location->id,
                'status_id' => $status->id,
                'condition' => 'Новый, в оригинальной упаковке',
                'image' => null,
            ]);
    }
}
