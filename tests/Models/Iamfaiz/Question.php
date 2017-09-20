<?php
namespace LaravelFillableRelations\Tests\Models\Iamfaiz;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Question extends Model
{
    use HasFillableRelations;
    protected $table = 'iamfaiz_questions';
    public $timestamps = false;
    protected $fillable = ['test_id', 'title', 'options'];
    protected $fillable_relations = ['test'];
    protected $casts = [
        'options' => 'array',
    ];

    function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        $attributes['options'] = $this->options;
        return $attributes;
    }
}