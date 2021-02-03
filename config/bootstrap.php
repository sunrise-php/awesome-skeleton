<?php declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Dotenv\Dotenv;

(function () {
    $root = realpath(__DIR__ . '/..');

    chdir($root);
    setlocale(LC_ALL, 'C.UTF-8');

    // enables strict development mode...
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    // enables autoload annotations...
    /** @scrutinizer ignore-deprecated */ AnnotationRegistry::registerLoader('class_exists');

    // loads environment variables from the `.env` file...
    (new Dotenv)->usePutenv()->load($root . '/.env');
})();
