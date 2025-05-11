<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 *
 * @property ?int $minimum_stock
 * @property boolean $alert
 * @property ?StorageRequirement $storageRequirements
 */
class Part extends Model
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
    protected $table = 'parts';
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
    public function partTemplate(): BelongsTo
    {
        return $this->belongsTo(PartTemplate::class, 'template_id');
    }

    /**
     * @return BelongsTo
     */
    public function rotationMethod(): BelongsTo
    {
        return $this->belongsTo(RotationMethod::class, 'rotation_method_id');
    }

    /**
     * @return MorphOne
     */
    public function storageRequirements(): MorphOne
    {
        return $this->morphOne(StorageRequirement::class, 'storage_requirements', 'requireable_type', 'requireable_id');
    }

    /**
     * @return HasMany
     */
    public function partBatches(): HasMany
    {
        return $this->hasMany(PartBatch::class);
    }

    /**
     * @return HasMany
     */
    public function partItems(): HasMany
    {
        return $this->hasMany(PartItem::class);
    }

    public function compatibleEquipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_part_compatability', 'part_id', 'equipment_id');
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

    /**
     * @return bool
     */
    public function getAlertAttribute(): bool
    {
        return $this->partItems()
                ->whereHas('storageLocation')
                ->count() < $this->minimum_stock;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
