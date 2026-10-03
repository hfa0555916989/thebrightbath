<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * The only database the test suite is ever allowed to touch.
     */
    public const TESTING_DATABASE = 'brightpath_testing';

    /**
     * Boot the application, then refuse to continue unless it is wired to the
     * local testing database. This runs before RefreshDatabase migrates anything.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $this->guardAgainstNonTestingDatabase($app);

        return $app;
    }

    private function guardAgainstNonTestingDatabase(Application $app): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        $host = config("database.connections.{$connection}.host");

        $problems = array_filter([
            $app->environment() !== 'testing'
                ? "APP_ENV is '{$app->environment()}', expected 'testing'" : null,
            $app->configurationIsCached()
                ? 'config is cached (bootstrap/cache/config.php) — run: php artisan config:clear' : null,
            $app->environmentFile() !== '.env.testing'
                ? 'the .env.testing file is missing — copy the testing block from .env.example' : null,
            $connection !== 'mysql'
                ? "DB connection is '{$connection}', expected 'mysql'" : null,
            $database !== self::TESTING_DATABASE
                ? "database is '{$database}', expected '".self::TESTING_DATABASE."'" : null,
            ! in_array($host, ['127.0.0.1', 'localhost', '::1'], true)
                ? "DB host '{$host}' is not local" : null,
        ]);

        if ($problems) {
            throw new RuntimeException(
                "Refusing to run tests against a non-testing database:\n - ".implode("\n - ", $problems)
            );
        }
    }
}
