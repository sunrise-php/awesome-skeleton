<?php declare(strict_types=1);

use function DI\add;
use function DI\string;

return [
    'doctrine.configuration.default.metadata_sources' => add([
        string('{app.root}/src/Bundle/Example/Entity'),
    ]),
];
