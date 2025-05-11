<?php

namespace Tests\Feature;

use App\Http\Requests\RotationMethodRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RotationMethodRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new RotationMethodRequest();

        $validData = [
            'name' => 'Тест',
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new RotationMethodRequest();

        $invalidData = [
            'name' => null,
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
