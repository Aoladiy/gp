<?php

namespace Tests\Feature;

use App\Http\Requests\PartItemRequest;
use App\Models\Part;
use App\Models\PartItemStatus;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PartItemRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new PartItemRequest();

        $validData = [
            'part_id' => Part::query()->value('id'),
            'status_id' => PartItemStatus::query()->value('id'),
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new PartItemRequest();

        $invalidData = [
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
