<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 *
 */
class Equipment extends Model
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
    protected $table = 'equipment';
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
    public function hasTags(): bool
    {
        return $this->tags()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsToMany
     */
    public function requiredPartTemplates(): BelongsToMany
    {
        return $this->belongsToMany(PartTemplate::class, 'equipment_required_templates', 'equipment_id', 'part_template_id');
    }

    /**
     * @return BelongsToMany
     */
    public function compatibleParts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'equipment_part_compatability', 'equipment_id', 'part_id');
    }

    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
