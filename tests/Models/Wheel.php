<?php
namespace LaravelFillableRelations\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Wheel extends Model
{
    use HasFillableRelations;
    public $timestamps = false;
    protected $fillable = ['size', 'vehicle_id'];
    protected $fillable_relations = ['car'];

    function car()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
