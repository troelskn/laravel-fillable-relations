<?php
namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\Vehicle;
use LaravelFillableRelations\Tests\Models\Wheel;

class BaseTests extends TestCase
{
    public function testFillVehicleNested()
    {
        $vehicle = Vehicle::create(
            [
                'name' => 'My nice car',
            ]
        );
        $vehicle->fill(
            [
                'wheels' => [
                    ['size' => 24]
                ]
            ]
        );
        $vehicle = $vehicle->fresh();
        $this->assertEquals(1, count($vehicle->wheels->toArray()));
    }

    public function testCreateVehiclePlain()
    {
        $vehicle = Vehicle::create(
            [
                'name' => 'My nice car'
            ]
        );
        $wheel = Wheel::create(
            [
                'size' => 24,
                'vehicle_id' => $vehicle->id,
            ]
        );
        $vehicle = $vehicle->fresh();
        $this->assertEquals(1, count($vehicle->wheels->toArray()));
    }

    public function testCreateVehicleNested()
    {
        $vehicle = Vehicle::create(
            [
                'name' => 'My nice car',
                'wheels' => [
                    ['size' => 24]
                ]
            ]
        );
        $vehicle = $vehicle->fresh();
        $this->assertEquals(1, count($vehicle->wheels->toArray()));
    }
}