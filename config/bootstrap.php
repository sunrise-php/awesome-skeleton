<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

set_error_handler(static function ($severity, $message, $filename, $line): never {
    throw new ErrorException($message, 0, $severity, $filename, $line);
});

(new Dotenv())->loadEnv(__DIR__ . '/../.env');
