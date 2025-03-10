<?php

declare(strict_types=1);

use App\Dictionary\TranslationDomain;
use Sunrise\Translator\Translator\DirectoryTranslator;

use function DI\add;
use function DI\create;
use function DI\string;

return [
    'translator.translators' => add([
        create(DirectoryTranslator::class)
            ->constructor(
                domain: TranslationDomain::APP,
                directory: string('{app.root}/resources/translations/app'),
            ),
    ]),
];
