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

    /**
     * Throws the exception if the given entity is NULL
     *
     * @param null|object $entity
     *
     * @return void
     *
     * @throws self
     */
    public static function assert(?object $entity) : void
    {
        if (null === $entity) {
            throw new self($entity, $violations);
        }
    }
}
