<?php

namespace Tests\Feature;

use App\Http\Requests\PartRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PartRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new PartRequest();

        $validData = [
            'name' => 'Тест',
            'article_number' => 'Тест',
            'minimum_stock' => 1,
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new PartRequest();

        $invalidData = [
            'article_number' => null,
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
