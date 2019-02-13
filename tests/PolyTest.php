<?php
namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\Forum;

class PolyTests extends TestCase
{
    public function testFillThroughMorphMany()
    {
        $forum = new Forum(
            [
                'title' => [
                    [
                        'text' => 'Lorem ipsum'
                    ]
                ]
            ]
        );
        $forum->save();
        $forum = $forum->fresh();
        $this->assertEquals('Lorem ipsum', $forum->title->first()->text);
    }
}