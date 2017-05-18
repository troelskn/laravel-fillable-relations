<?php
namespace LaravelFillableRelations\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Vehicle extends Model
{
    use HasFillableRelations;
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $fillable_relations = ['wheels'];

    function wheels()
    {
        return $this->hasMany(Wheel::class);
    }
}