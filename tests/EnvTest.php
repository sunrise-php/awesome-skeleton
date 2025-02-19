<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

final class EnvTest extends TestCase
{
    public function testAppEnvIsTest(): void
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'dev';

        self::assertSame('test', $appEnv);
    }
}
