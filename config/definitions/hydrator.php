<?php

declare(strict_types=1);

use Sunrise\Hydrator\Dictionary\ContextKey;

use function DI\get;

return [
    'hydrator.context' => [
        ContextKey::TIMESTAMP_FORMAT => get('app.input_timestamp_format'),
        ContextKey::TIMEZONE => get('app.timezone_identifier'),
    ],
];
