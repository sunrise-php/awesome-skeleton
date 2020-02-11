<?php declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use function DI\create;
use function DI\get;
use function DI\string;

return [
    'twig' => create(Environment::class)->constructor(
        get('twig.loader'),
        get('twig.configuration')
    ),

    'twig.loader' => create(FilesystemLoader::class)->constructor(
        get('twig.configuration.loader_paths')
    ),

    'twig.configuration' => [
        'debug'            => get('twig.configuration.debug'),
        'charset'          => get('twig.configuration.charset'),
        'strict_variables' => get('twig.configuration.strict_variables'),
        'autoescape'       => get('twig.configuration.autoescape'),
        'cache'            => get('twig.configuration.cache'),
        'auto_reload'      => get('twig.configuration.auto_reload'),
        'optimizations'    => get('twig.configuration.optimizations'),
    ],

    'twig.configuration.loader_paths' => [
        string('{app.root}/resources/views'),
    ],

    'twig.configuration.debug' => false,
    'twig.configuration.charset' => 'UTF-8',
    'twig.configuration.strict_variables' => false,
    'twig.configuration.autoescape' => 'html',
    'twig.configuration.cache' => false,
    'twig.configuration.auto_reload' => null,
    'twig.configuration.optimizations' => -1,
];
