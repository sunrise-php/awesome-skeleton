<?php declare(strict_types=1);

use function DI\add;
use function DI\string;

return [
    'router.configuration.metadata_sources' => add([
        string('{app.root}/src/Bundle/Example/Controller'),
    ]),
];
