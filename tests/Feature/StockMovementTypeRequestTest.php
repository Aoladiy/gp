<?php

namespace Tests\Feature;

use App\Http\Requests\StockMovementTypeRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StockMovementTypeRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new StockMovementTypeRequest();

        $validData = [
            'name' => 'Тест',
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new StockMovementTypeRequest();

        $invalidData = [
            'name' => null,
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
