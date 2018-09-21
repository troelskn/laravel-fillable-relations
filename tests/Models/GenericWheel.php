<?php
/**
 * Created by PhpStorm.
 * User: alexandrucalin
 * Date: 9/21/18
 * Time: 17:07
 */
namespace LaravelFillableRelations\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;
use LaravelFillableRelations\Tests\Models\Vehicle;

class GenericWheel extends Model
{
    use HasFillableRelations;

    protected $table = 'generic_wheel';
    public $timestamps = false;
    protected $fillable = ['size'];
    protected $fillable_relations = ['fancyCar'];

    function fancyCar()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}

