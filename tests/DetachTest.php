<?php

namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\Vehicle;
use LaravelFillableRelations\Tests\Models\Wheel;

class DetachTests extends TestCase
{
    protected $vehicle;

    public function setUp(): void
    {
        parent::setUp();

        $this->vehicle = Vehicle::create(
            [
                'name' => 'My nice car',
            ]
        );

        for ($i = 0; $i < 4; $i++) {
            Wheel::create(
                [
                    'car' => $this->vehicle,
                    'size' => 24,
                ]
            );
        }
    }

    public function testDetachWithEmptyArray()
    {
        $this->assertEquals(4, count($this->vehicle->wheels->toArray()));

        $this->vehicle->fill([
            'wheels' => [],
        ])->save();

        $this->vehicle = Vehicle::find($this->vehicle->id);

        $this->assertEquals(0, count($this->vehicle->wheels->toArray()));
    }

    public function testDoNothingWithNullValue()
    {
        $this->assertEquals(4, count($this->vehicle->wheels->toArray()));

        $this->vehicle->fill([
            'wheels' => null,
        ])->save();

        $this->vehicle = Vehicle::find($this->vehicle->id);

        $this->assertEquals(4, count($this->vehicle->wheels->toArray()));
    }
}
