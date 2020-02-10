<?php declare(strict_types=1);

namespace App\Exception;

/**
 * ResourceNotFoundException
 */
final class ResourceNotFoundException extends AbstractException
{

    /**
     * Constructor of the class
     *
     * @param null|string $message
     */
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'The requested resource was not found');
    }
}
