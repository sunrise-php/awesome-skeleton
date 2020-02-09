<?php declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Dotenv\Dotenv;

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

AnnotationRegistry::registerLoader('class_exists');

(new Dotenv(true))->load(__DIR__ . '/../.env');
