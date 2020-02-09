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

    // A path or an array of paths where to look for templates.
    'twig.configuration.loader_paths' => [
        string('{app.root}/resources/views'),
    ],

    // When set to true, it automatically set "auto_reload" to true as well (default to false).
    'twig.configuration.debug' => false,

    // The charset used by the templates (default to UTF-8).
    'twig.configuration.charset' => 'UTF-8',

    // Whether to ignore invalid variables in templates (default to false).
    'twig.configuration.strict_variables' => false,

    // Whether to enable auto-escaping (default to html):
    // * false: disable auto-escaping
    // * html, js: set the autoescaping to one of the supported strategies
    // * name: set the autoescaping strategy based on the template name extension
    // * PHP callback: a PHP callback that returns an escaping strategy based on the template "name"
    'twig.configuration.autoescape' => 'html',

    // An absolute path where to store the compiled templates,
    // a \Twig\Cache\CacheInterface implementation,
    // or false to disable compilation cache (default).
    'twig.configuration.cache' => false,

    // Whether to reload the template if the original source changed.
    // If you don't provide the auto_reload option, it will be
    // determined automatically based on the debug value.
    'twig.configuration.auto_reload' => null,

    // A flag that indicates which optimizations to apply
    // (default to -1 which means that all optimizations are enabled;
    // set it to 0 to disable).
    'twig.configuration.optimizations' => -1,
];
