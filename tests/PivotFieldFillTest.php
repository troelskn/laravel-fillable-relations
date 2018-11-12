<?php
/**
 * Created by PhpStorm.
 * User: alexandrucalin
 * Date: 9/21/18
 * Time: 14:00
 */

namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\GenericWheel;
use LaravelFillableRelations\Tests\Models\Vehicle;

class PivotFieldFillTest extends TestCase
{
    private $fancyCarData = [
        'name' => 'Circus Theme Ride',
        'fancyWheels' => [
            [
                'size' => 10,
                'pivot' => ['colour' => 'red', 'gold_plated' => 0]
            ],
            [
                'size' => 10,
                'pivot' => ['colour' => 'pink', 'silver_plated' => 1]
            ],
            [
                'size' => 10,
            ],
            [
                'size' => 10,
                'pivot' => ['colour' => 'yellow', 'gold_plated' => 1]
            ]
        ]
    ];

    private $pivotColumns = [
        'colour',
        'silver_plated',
        'gold_plated',
    ];

    public function testFancyWheels()
    {
        $this->mockGenericWheels();
        $fancyCar = $this->mockFancyCar();

        $fancyWheelsArr = $fancyCar->fancyWheels()->get()->toArray();
        foreach ($this->fancyCarData['fancyWheels'] as $fancyWheelKey => $fancyWheel) {
            $this->assertEquals(
                $fancyWheelsArr[$fancyWheelKey]['size'],
                $fancyWheel['size']
            );

            foreach ($this->pivotColumns as $pivotColumn) {
                $pivotField = $this->getPivotField($fancyWheel, $pivotColumn);
                if ($pivotField) {
                    $origPivotField = $fancyWheelsArr[$fancyWheelKey]['pivot'][$pivotColumn];
                    $this->assertEquals(
                        $pivotField,
                        $origPivotField
                    );
                }
            }
        }
    }

    private function getPivotField($model, $key)
    {
        $p = 'pivot';
        return isset($model[$p][$key]) ? $model[$p][$key] : false;
    }

    private function mockGenericWheels()
    {
        for ($i = 10; $i < 20; $i++) {
            $wheels = new GenericWheel();
            $wheels->fill(['size' => $i]);
            $wheels->save();
        }
    }

    private function mockFancyCar()
    {
        $fancyCar = new Vehicle();
        $fancyCar->fill($this->fancyCarData);
        $fancyCar->save();

        return $fancyCar;
    }
}
