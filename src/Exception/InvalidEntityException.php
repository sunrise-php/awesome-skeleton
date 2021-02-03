<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Import classes
 */
use Sunrise\Http\Router\Exception\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Import functions
 */
use function get_class;

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
    public static function assertValid(object $entity, ValidatorInterface $validator) : void
    {
        $violations = self::convertViolationsToArray($validator->validate($entity));
        if ([] === $violations) {
            return;
        }

        throw new self('Invalid Entity ' . get_class($entity), [
            'violations' => $violations,
        ]);
    }

    /**
     * Converts the given violation list object to array
     *
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     */
    private static function convertViolationsToArray(ConstraintViolationListInterface $violations) : array
    {
        $result = [];
        foreach ($violations as $violation) {
            $result[] = [
                'message' => $violation->getMessage(),
                'property' => $violation->getPropertyPath(),
            ];
        }

        return $result;
    }
}
