<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            RayServiceProvider::class,
        ];
    }
}
