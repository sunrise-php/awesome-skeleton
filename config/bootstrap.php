<?php declare(strict_types=1);

error_reporting(E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED);

set_error_handler(function ($severity, $message, $file, $line) {
    if ($severity & error_reporting()) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
});

require __DIR__ . '/../vendor/autoload.php';
