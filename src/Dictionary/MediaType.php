<?php

declare(strict_types=1);

namespace App\Dictionary;

use Sunrise\Coder\MediaTypeInterface;

enum MediaType: string implements MediaTypeInterface
{
    case HTML = 'text/html';
    case JSON = 'application/json';

    public function getIdentifier(): string
    {
        return $this->value;
    }
}
