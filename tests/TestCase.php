<?php

namespace Modstore\LaravelEnumJs\Tests;

use Modstore\LaravelEnumJs\LaravelEnumJsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelEnumJsServiceProvider::class,
        ];
    }
}
