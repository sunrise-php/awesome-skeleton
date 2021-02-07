<?php declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Dotenv\Dotenv;

chdir(__DIR__ . '/..');
setlocale(LC_ALL, 'C.UTF-8');

// enables strict development mode...
// good code shouldn't contain notices, warnings and etc.
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// loads environment variables from the `.env` file...
(new Dotenv)->usePutenv()->load(__DIR__ . '/../.env');
