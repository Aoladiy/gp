<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\PartTemplate;
use App\Models\RotationMethod;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rotation = RotationMethod::query()
            ->firstOrCreate(['name' => 'FIFO']);
        $template = PartTemplate::query()
            ->where('name', 'Фильтр масла')->first();

        Part::query()
            ->updateOrCreate([
                'name' => 'Фильтр масла Mann W914/2',
                'article_number' => 'W9142',
            ], [
                'description' => 'Оригинальный масляный фильтр для дизельных двигателей',
                'minimum_stock' => 5,
                'rotation_method_id' => $rotation->id,
                'template_id' => $template->id,
            ]);
    }
}
