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
        $rotation = RotationMethod::query()->firstOrCreate(['name' => 'FIFO']);

        $parts = [
            ['template' => 'Фильтр масла', 'name' => 'Фильтр масла Mann W914/2', 'article' => 'W9142'],
            ['template' => 'Стартер', 'name' => 'Стартер Bosch 0986020340', 'article' => 'BOSCH-S01'],
            ['template' => 'Турбина', 'name' => 'Турбина Garrett GT1549', 'article' => 'GAR-TB49'],
            ['template' => 'Редуктор', 'name' => 'Редуктор ZF 16S', 'article' => 'ZF16S'],
            ['template' => 'Термостат', 'name' => 'Термостат Gates TH11287G1', 'article' => 'TH11287'],
        ];

        foreach ($parts as $data) {
            $template = PartTemplate::query()->where('name', $data['template'])->first();
            Part::query()->updateOrCreate([
                'article_number' => $data['article'],
            ], [
                'name' => $data['name'],
                'description' => 'Описание для ' . $data['name'],
                'minimum_stock' => rand(3, 10),
                'rotation_method_id' => $rotation->id,
                'template_id' => $template?->id,
            ]);
        }
    }
}
