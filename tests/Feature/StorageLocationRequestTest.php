<?php

namespace Tests\Feature;

use App\Http\Requests\StorageLocationRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StorageLocationRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new StorageLocationRequest();

        $validData = [
            'name' => 'Тест',
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new StorageLocationRequest();

        $invalidData = [
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
