<?php declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

chdir(__DIR__ . '/..');

// enables strict development mode...
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// loads environment variables...
(new Dotenv)->usePutenv()->load(__DIR__ . '/../.env');
