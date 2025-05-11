<?php

namespace Tests\Feature;

use App\Http\Requests\StockMovementRequest;
use App\Models\PartItem;
use App\Models\StockMovementType;
use App\Models\StorageLocation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StockMovementRequestTest extends TestCase
{
    public function test_valid_data_passes_validation()
    {
        $request = new StockMovementRequest();

        $validData = [
            'user_id' => User::query()->where('name', '=', 'testUser')->value('id') ?? User::query()->value('id'),
            'part_item_id' => PartItem::query()->value('id'),
            'stock_movement_type_id' => StockMovementType::query()->value('id'),
            'from_location_id' => StorageLocation::query()->value('id'),
            'to_location_id' => StorageLocation::query()->value('id') + 1,
            'moved_at' => Carbon::now(),
            'note' => 'Перемещение между складами',
        ];

        $validator = Validator::make($validData, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation()
    {
        $request = new StockMovementRequest();

        $invalidData = [
            'user_id' => User::query()->max('id') + 1,
        ];

        $validator = Validator::make($invalidData, $request->rules());
        $this->assertFalse($validator->passes());
    }
}
