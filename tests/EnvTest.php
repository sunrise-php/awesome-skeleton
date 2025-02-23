<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

final class EnvTest extends TestCase
{
    public function testAppEnvIsTest(): void
    {
        self::assertSame('test', $_ENV['APP_ENV'] ?? null);
    }
}
