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
        $engine = PartTemplate::query()
            ->updateOrCreate(['name' => 'Двигатель']);
        $gearbox = PartTemplate::query()
            ->updateOrCreate([
                'name' => 'Коробка передач',
                'parent_id' => $engine->id,
            ]);
        PartTemplate::query()
            ->updateOrCreate(['name' => 'Фильтр масла', 'parent_id' => $engine->id]);
        PartTemplate::query()
            ->updateOrCreate(['name' => 'Сцепление', 'parent_id' => $gearbox->id]);
    }
}
