<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property int $from_location_id
 * @property int $to_location_id
 * @property int $stock_movement_type_id
 */
class StockMovement extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    protected $table = 'stock_movements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    /**
     * @var string[]
     */
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /**
     * @param StockMovement $movement
     * @return int|null
     */
    protected static function resolveTypeId(StockMovement $movement): ?int
    {
        $type = match (true) {
            is_null($movement->from_location_id) && !is_null($movement->to_location_id) => 'Поступление',
            !is_null($movement->from_location_id) && is_null($movement->to_location_id) => 'Списание',
            !is_null($movement->from_location_id) && !is_null($movement->to_location_id) => 'Перемещение',
            default => null,
        };

        return $type
            ? StockMovementType::query()->where('name', $type)->value('id')
            : null;
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (StockMovement $movement) {
            if (!$movement->stock_movement_type_id) {
                $movement->stock_movement_type_id = self::resolveTypeId($movement);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo
     */
    public function partItem(): BelongsTo
    {
        return $this->belongsTo(PartItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function stockMovementType(): BelongsTo
    {
        return $this->belongsTo(StockMovementType::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'from_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'to_location_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
