<?php
namespace LaravelFillableRelations\Tests\Models\ThreeLevelFill;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class ToolFieldChoice extends Model
{
    use HasFillableRelations;
    protected $table = 'threelevelfill_choices';
    protected $fillable = ['name', 'field_id'];
    protected $fillable_relations = ['field'];

    function field()
    {
        return $this->belongsTo(ToolField::class, 'field_id');
    }
}