<?php

declare(strict_types=1);

use Sunrise\Http\Router\Command\RouterClearDescriptorsCacheCommand;
use Sunrise\Http\Router\Command\RouterListRoutesCommand;
use Sunrise\Http\Router\OpenApi\Command\RouterOpenApiBuildDocumentCommand;

use function DI\autowire;

return [
    'commands' => [
        autowire(RouterClearDescriptorsCacheCommand::class),
        autowire(RouterListRoutesCommand::class),
        autowire(RouterOpenApiBuildDocumentCommand::class),
    ],
];
