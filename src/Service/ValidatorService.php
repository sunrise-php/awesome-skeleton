<?php declare(strict_types=1);

namespace App\Service;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use App\Exception\InvalidEntityException;

/**
 * ValidatorService
 */
final class ValidatorService
{
    use ContainerAwareTrait;

    /**
     * Validates the given entity
     *
     * @param object $entity
     *
     * @return void
     *
     * @throws InvalidEntityException
     */
    public function validate(object $entity) : void
    {
        $violations = $this->container->get('validator')->validate($entity);

        if (0 === $violations->count()) {
            return;
        }

        throw new InvalidEntityException($entity, $violations);
    }
}
