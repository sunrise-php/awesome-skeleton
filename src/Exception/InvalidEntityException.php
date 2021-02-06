<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Import classes
 */
use Sunrise\Http\Router\Exception\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * InvalidEntityException
 */
final class InvalidEntityException extends BadRequestException
{

    /**
     * Throws the exception if the given entity isn't valid
     *
     * @param object $entity
     * @param ValidatorInterface $validator
     *
     * @return void
     *
     * @throws self
     */
    public static function assert(object $entity, ValidatorInterface $validator) : void
    {
        $violations = $validator->validate($entity);
        if (0 === $violations->count()) {
            return;
        }

        throw new self('Invalid Entity', [
            'violations' => $violations->getIterator()->getArrayCopy(),
        ]);
    }
}
