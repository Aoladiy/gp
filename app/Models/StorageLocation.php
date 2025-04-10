<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property string $name
 * @property ?StorageLocation $parent
 * @property ?StorageRequirement $storageRequirements
 */
class StorageLocation extends Model
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
    protected $table = 'storage_locations';
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
    public function hasParent(): bool
    {
        return $this->parent()->exists();
    }

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
    public function parent(): BelongsTo
    {
        return $this->belongsTo($this::class);
    }

    /**
     * @return HasMany
     */
    public function partItems(): HasMany
    {
        return $this->hasMany(PartItem::class);
    }

    /**
     * @return HasMany
     */
    public function fromForStockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'from_location_id');
    }

    /**
     * @return HasMany
     */
    public function toForStockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'to_location_id');
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
