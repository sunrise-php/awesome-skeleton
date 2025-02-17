<?php

declare(strict_types=1);

namespace App\Dictionary;

use Sunrise\Http\Router\LanguageInterface;

enum LanguageCode: string implements LanguageInterface
{
    case English = 'en';
    case Russian = 'ru';
    case Serbian = 'sr';

    public function getCode(): string
    {
        return $this->value;
    }
}
