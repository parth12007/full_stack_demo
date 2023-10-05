<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Dotenv\Dotenv;

abstract class TestCase extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        Dotenv::createMutable(base_path(), '.env.testing')->load();
        config()->set('logging.default', 'testing');
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
