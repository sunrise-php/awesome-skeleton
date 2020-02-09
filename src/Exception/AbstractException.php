<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Import classes
 */
use RuntimeException;

/**
 * AbstractException
 */
abstract class AbstractException extends RuntimeException implements ExceptionInterface
{
}
