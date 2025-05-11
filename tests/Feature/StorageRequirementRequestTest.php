<?php

namespace Tests\Feature;

use App\Http\Requests\StorageRequirementRequest;
use App\Models\LightingLevel;
use App\Models\Part;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StorageRequirementRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new StorageRequirementRequest();

        $validData = [
            'requireable_id' => 5,
            'requireable_type' => Part::class,
            'temperature_min' => 5.5,
            'temperature_max' => 25.0,
            'humidity_min' => 30.0,
            'humidity_max' => 80.0,
            'lighting_level_id' => LightingLevel::query()->value('id'),
            'ventilation_level' => 'Средний',
            'fire_safety_class' => 'Класс А',
            'note' => 'Дополнительные условия хранения',
        ];


        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new StorageRequirementRequest();

        $invalidData = [
        ];


        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
