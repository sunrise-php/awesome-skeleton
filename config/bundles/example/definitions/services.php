<?php declare(strict_types=1);

use App\Bundle\Example\Service\EntryManager;
use App\Bundle\Example\Service\EntrySerializer;

use function DI\autowire;
use function DI\create;

return [
    'entryManager' => autowire(EntryManager::class),
    'entrySerializer' => create(EntrySerializer::class),
];
