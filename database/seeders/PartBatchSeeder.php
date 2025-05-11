<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\PartBatch;
use App\Models\PartItem;
use App\Models\PartItemStatus;
use App\Models\StorageLocation;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PartBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        $part = Part::query()
            ->where('article_number', 'W9142')->first();
        $location = StorageLocation::query()
            ->where('name', 'Главный склад')->first();
        $statusId = PartItemStatus::query()
            ->where('name', 'В наличии')->value('id');

        if (!$part || !$location || !$statusId) {
            throw new Exception('Не найдены связанные данные (part, location или status)');
        }

        // Создаём партию
        $batch = PartBatch::query()
            ->updateOrCreate([
                'batch_number' => 'BATCH-2025-W9142',
            ], [
                'part_id' => $part->id,
                'received_at' => Carbon::now()->subDays(7),
                'expiry_date' => Carbon::now()->addYears(1),
            ]);

        // Кол-во создаваемых деталей
        $quantity = 10;

        for ($i = 1; $i <= $quantity; $i++) {
            PartItem::query()
                ->updateOrCreate([
                    'serial_number' => 'W9142-B25-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                ], [
                    'part_id' => $part->id,
                    'part_batch_id' => $batch->id,
                    'storage_location_id' => $location->id,
                    'status_id' => $statusId,
                    'condition' => 'Новое. С партии ' . $batch->batch_number,
                    'image' => null,
                ]);
        }
    }
}
