<?php
namespace LaravelFillableRelations\Tests;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class TestServiceProvider extends BaseServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/database/migrations'
        );
    }
}