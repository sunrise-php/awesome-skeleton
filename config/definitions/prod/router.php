<?php

declare(strict_types=1);

use Psr\SimpleCache\CacheInterface;

use function DI\get;

return [
    'router.descriptor_loader.cache' => get(CacheInterface::class),
];
