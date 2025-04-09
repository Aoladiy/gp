<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property ?PartTemplate $parent
 */
class PartTemplate extends Model
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
    protected $table = 'part_templates';
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
    public function hasNested(): bool
    {
        return $this->nested()->exists();
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
    public function nested(): HasMany
    {
        return $this->hasMany($this::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class, 'template_id');
    }

    public function requiredByEquipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_required_templates', 'part_template_id', 'equipment_id');
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
