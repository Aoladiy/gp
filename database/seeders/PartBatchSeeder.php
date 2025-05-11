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
        $statusId = PartItemStatus::query()->where('name', 'В наличии')->value('id');

        $parts = Part::query()->get();
        foreach ($parts as $part) {
            $batch = PartBatch::query()->updateOrCreate([
                'batch_number' => 'BATCH-' . $part->article_number,
            ], [
                'part_id' => $part->id,
                'received_at' => Carbon::now()->subDays(rand(1, 15)),
                'expiry_date' => Carbon::now()->addMonths(rand(6, 24)),
            ]);

            $quantity = rand(5, 15);
            for ($i = 1; $i <= $quantity; $i++) {
                PartItem::query()->updateOrCreate([
                    'serial_number' => $part->article_number . '-SN-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                ], [
                    'part_id' => $part->id,
                    'part_batch_id' => $batch->id,
                    'storage_location_id' => StorageLocation::query()->inRandomOrder()->value('id'),
                    'status_id' => $statusId,
                    'condition' => 'Новое. С партии ' . $batch->batch_number,
                    'image' => null,
                ]);
            }
        }
    }
}
