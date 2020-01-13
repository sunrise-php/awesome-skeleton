<?php declare(strict_types=1);

use App\Factory\TwigFactory;

use function DI\factory;
use function DI\get;
use function DI\string;

return [
    'twig' => factory([TwigFactory::class, 'createEnvironment'])
        ->parameter('params', get('twig.configuration')),

    'twig.configuration' => [
        'loader' => [
            'paths' => get('twig.configuration.loader.paths'),
        ],
    ],

    'twig.configuration.loader.paths' => [
        string('{app.root}/resources/views'),
    ],
];
