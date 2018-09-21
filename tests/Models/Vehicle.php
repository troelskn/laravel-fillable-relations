<?php
namespace LaravelFillableRelations\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Vehicle extends Model
{
    use HasFillableRelations;
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $fillable_relations = ['wheels', 'fancyWheels'];

    function wheels()
    {
        return $this->hasMany(Wheel::class);
    }

    function fancyWheels()
    {
        return $this->belongsToMany(
            GenericWheel::class,
            'vehicles_wheels',
            'vehicle_id',
            'wheel_id'
        )->withPivot(['colour', 'gold_plated', 'silver_plated']);
    }
}
