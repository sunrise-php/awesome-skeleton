<?php

declare(strict_types=1);

use App\Dictionary\DomainContracts;

use function DI\env;

return [
    'app.root' => realpath(__DIR__ . '/../..'),

    'app.env' => env('APP_ENV'),
    'app.name' => env('APP_NAME'),

    'app.input_timestamp_format' => DomainContracts::INPUT_TIMESTAMP_FORMAT,
    'app.output_timestamp_format' => DomainContracts::OUTPUT_TIMESTAMP_FORMAT,
    'app.timezone' => DomainContracts::TIMEZONE,
];
