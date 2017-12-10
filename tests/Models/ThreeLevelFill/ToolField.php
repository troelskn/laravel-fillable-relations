<?php
namespace LaravelFillableRelations\Tests\Models\ThreeLevelFill;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class ToolField extends Model
{
    use HasFillableRelations;
    protected $table = 'threelevelfill_fields';
    protected $fillable = ['name', 'tool_id'];
    protected $fillable_relations = ['tool', 'choices'];

    function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id');
    }

    public function choices()
    {
        return $this->hasMany(ToolFieldChoice::class, 'field_id', 'id');
    }
}