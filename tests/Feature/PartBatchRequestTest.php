<?php

namespace Tests\Feature;

use App\Http\Requests\PartBatchRequest;
use App\Models\Part;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PartBatchRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new PartBatchRequest();

        $validData = [
            'part_id' => Part::query()->value('id'),
            'quantity' => 1,
            'batch_number' => '123',
            'received_at' => Carbon::now(),
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new PartBatchRequest();

        $invalidData = [
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
