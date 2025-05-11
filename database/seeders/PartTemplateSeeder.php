<?php

namespace Database\Seeders;

use App\Models\PartTemplate;
use Illuminate\Database\Seeder;

class PartTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engine = PartTemplate::query()->updateOrCreate(['name' => 'Двигатель']);
        $gearbox = PartTemplate::query()->updateOrCreate(['name' => 'Коробка передач', 'parent_id' => $engine->id]);
        $cooling = PartTemplate::query()->updateOrCreate(['name' => 'Система охлаждения', 'parent_id' => $engine->id]);

        $subTemplates = [
            ['name' => 'Фильтр масла', 'parent_id' => $engine->id],
            ['name' => 'Стартер', 'parent_id' => $engine->id],
            ['name' => 'Турбина', 'parent_id' => $engine->id],
            ['name' => 'Сцепление', 'parent_id' => $gearbox->id],
            ['name' => 'Редуктор', 'parent_id' => $gearbox->id],
            ['name' => 'Радиатор', 'parent_id' => $cooling->id],
            ['name' => 'Термостат', 'parent_id' => $cooling->id],
        ];

        foreach ($subTemplates as $data) {
            PartTemplate::query()->updateOrCreate($data);
        }
    }
}
