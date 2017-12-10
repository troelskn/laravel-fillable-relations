<?php
namespace LaravelFillableRelations\Tests\Models\ThreeLevelFill;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class ToolField extends Model
{
    use HasFillableRelations;
    protected $table = 'threelevelfill_fields';
    protected $fillable = ['name'];
    protected $fillable_relations = ['choices'];

    public function choices()
    {
        return $this->hasMany(static::class . 'Choice');
    }
}