<?php

namespace Tests\Feature;

use App\Http\Requests\EquipmentRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class EquipmentRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new EquipmentRequest();

        $validData = [
            'name' => 'Тест',
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new EquipmentRequest();

        $invalidData = [
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
