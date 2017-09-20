<?php
namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\Iamfaiz\Test;
use LaravelFillableRelations\Tests\Models\Iamfaiz\Question;

/**
 * Implement this real-world scenario:
 * https://laracasts.com/discuss/channels/eloquent/how-to-automatically-insert-relations-based-on-a-nested-array-in-laravel
 */
class IamfaizTests extends TestCase
{
    public function testFillNestedStructure()
    {
        $data = [
            "name" => "First Test",
            "preparation" => "First Test prep",
            "questions" => [
                [
                    "title" => "Some question",
                    "options" => [
                        "a",
                        "b",
                        "c",
                        "d"
                    ]
                ],
                [
                    "title" => "Another question",
                    "options" => [
                        "e",
                        "f",
                        "g",
                        "h"
                    ]
                ]
            ]
        ];
        $test = Test::create($data);
        $data2 = $test->fresh()->toArray();
        $this->assertEquals($data['name'], $data2['name']);
        $this->assertEquals($data['preparation'], $data2['preparation']);
        $this->assertEquals(count($data['questions']), count($data2['questions']));
        $this->assertEquals($data['questions'][0]['title'], $data2['questions'][0]['title']);
        $this->assertEquals($data['questions'][0]['options'], $data2['questions'][0]['options']);
    }
}