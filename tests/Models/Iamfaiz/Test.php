<?php
namespace LaravelFillableRelations\Tests\Models\Iamfaiz;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Test extends Model
{
    use HasFillableRelations;
    protected $table = 'iamfaiz_tests';
    public $timestamps = false;
    protected $fillable = ['name', 'preparation'];
    protected $fillable_relations = ['questions'];

    function questions()
    {
        return $this->hasMany(Question::class, 'test_id');
    }

    function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['questions'] = $this->questions->map->getAttributes()->toArray();
        return $attributes;
    }
}