<?php
namespace LaravelFillableRelations\Tests;

use LaravelFillableRelations\Tests\Models\ThreeLevelFill\Tool;
use LaravelFillableRelations\Tests\Models\ThreeLevelFill\ToolField;
use LaravelFillableRelations\Tests\Models\ThreeLevelFill\ToolFieldChoice;

/**
 * Demonstrate error with three levels, as reported in:
 * https://github.com/troelskn/laravel-fillable-relations/issues/5
 */
class ThreeLevelFillTests extends TestCase
{
    public function testWithoutThirdLevel()
    {
        $tool = new Tool(
            [
                'name' => 'vacuum',
                'fields' => [
                    [
                        'name' => 'full'
                    ]
                ]
            ]
        );
        $tool->save();
        $tool = $tool->fresh();
        $field = ToolField::select()->get()->first();
        $this->assertEquals($field->tool_id, $tool->id);
    }

    public function testWithThirdLevel()
    {
        $tool = new Tool(
            [
                'name' => 'vacuum',
                'fields' => [
                    [
                        'name' => 'full',
                        'choices' => [
                            [
                                'name' => 'on'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $tool->save();
        $tool = $tool->fresh();
        $field = ToolField::select()->get()->first();
        $this->assertEquals($field->tool_id, $tool->id);
        $choice = ToolFieldChoice::select()->get()->first();
        $this->assertEquals($choice->field_id, $field->id);
    }
}