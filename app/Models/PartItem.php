<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 *
 * @property ?int $storage_location_id
 * @property int $id
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
