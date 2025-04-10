<?php

namespace App\Models;

use App\Exceptions\StorageLocationException;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property ?int $storage_location_id
 * @property int $status_id
 * @property ?StorageLocation $storageLocation
 * @property ?StorageRequirement $storageRequirements
 * @property Part $part
 */
class PartItem extends Model
{
    use CrudTrait;
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (PartItem $partItem) {
            if (!$partItem->storage_location_id) {
                return;
            }
            StockMovement::query()
                ->create([
                    'user_id' => backpack_user()->id ?? null,
                    'part_item_id' => $partItem->id,
                    'from_location_id' => null,
                    'to_location_id' => $partItem->storage_location_id,
                    'moved_at' => now(),
                    'note' => 'Автоматически создано при добавлении детали',
                ]);
        });

        static::updated(function (PartItem $partItem) {
            if ($partItem->isDirty('storage_location_id')) {
                $from = $partItem->getOriginal('storage_location_id');
                $to = $partItem->storage_location_id;
                StockMovement::query()
                    ->create([
                        'user_id' => backpack_user()->id ?? null,
                        'part_item_id' => $partItem->id,
                        'from_location_id' => $from,
                        'to_location_id' => $to,
                        'moved_at' => now(),
                        'note' => 'Автоматически создано при перемещении детали',
                    ]);
            }
        });
        static::saving(function (PartItem $partItem) {
            if (
                ($partItem->hasStorageRequirements() || $partItem->part->hasStorageRequirements())
                && $partItem->storageLocation?->hasStorageRequirements()
            ) {
                $requirements = $partItem->storageRequirements ?? $partItem->part->storageRequirements;
                $conditions = $partItem->storageLocation->storageRequirements;

                if (
                    $requirements->temperature_min && $conditions->temperature_min
                    &&
                    $requirements->temperature_min > $conditions->temperature_min
                ) {
                    throw new StorageLocationException('Недопустимо низкая температура в назначаемой складской локации. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->temperature_max && $conditions->temperature_max
                    &&
                    $requirements->temperature_max < $conditions->temperature_max
                ) {
                    throw new StorageLocationException('Недопустимо высокая температура в назначаемой складской локации. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->humidity_min && $conditions->humidity_min
                    &&
                    $requirements->humidity_min > $conditions->humidity_min
                ) {
                    throw new StorageLocationException('Недопустимо низкая влажность в назначаемой складской локации. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->humidity_max && $conditions->humidity_max
                    &&
                    $requirements->humidity_max < $conditions->humidity_max
                ) {
                    throw new StorageLocationException('Недопустимо высокая влажность в назначаемой складской локации. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->lightingLevel?->name && $conditions?->lightingLevel->name
                    &&
                    $requirements->lightingLevel->name !== $conditions->lightingLevel->name
                ) {
                    throw new StorageLocationException('Уровень освещенности не соответствует требованиям. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->ventilation_level && $conditions->ventilation_level
                    &&
                    $requirements->ventilation_level !== $conditions->ventilation_level
                ) {
                    throw new StorageLocationException('Уровень вентиляции не соответствует требованиям. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }

                if (
                    $requirements->fire_safety_class && $conditions->fire_safety_class
                    &&
                    $requirements->fire_safety_class !== $conditions->fire_safety_class
                ) {
                    throw new StorageLocationException('Класс пожарной безопасности не соответствует требованиям. Требования: ' . $requirements . ' Условия: ' . $conditions);
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    protected $table = 'part_items';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    /**
     * @var string[]
     */
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return bool
     */
    public function hasStorageRequirements(): bool
    {
        return $this->storageRequirements()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * @return BelongsTo
     */
    public function partBatch(): BelongsTo
    {
        return $this->belongsTo(PartBatch::class);
    }

    /**
     * @return BelongsTo
     */
    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PartItemStatus::class, 'status_id');
    }

    /**
     * @return MorphOne
     */
    public function storageRequirements(): MorphOne
    {
        return $this->morphOne(StorageRequirement::class, 'storage_requirements', 'requireable_type', 'requireable_id');
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
