<?php
namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\Vehicle;
use LaravelFillableRelations\Tests\Models\Wheel;

class HasOneTests extends TestCase
{
    public function testCreateVehicleNestedHasOne()
    {
        $vehicle = Vehicle::create(
            [
                'name' => 'My nice car',
                'engine' => ['horsepower' => 245]
            ]
        );
        $vehicle = $vehicle->fresh();
        $this->assertEquals(245, $vehicle->engine->horsepower);
    }
}