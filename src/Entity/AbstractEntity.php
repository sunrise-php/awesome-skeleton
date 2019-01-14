<?php declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * AbstractEntity
 */
abstract class AbstractEntity
{

    /**
     * The entity validator
     *
     * @var null|ValidatorInterface
     */
    protected $validator;

    /**
     * Gets the entity validator
     *
     * @return ValidatorInterface
     */
    public function getValidator() : ValidatorInterface
    {
        if (empty($this->validator)) {
            $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        }

        return $this->validator;
    }

    /**
     * Validates the entity
     *
     * @return ConstraintViolationListInterface
     */
    public function validate() : ConstraintViolationListInterface
    {
        return $this->getValidator()
        ->validate($this);
    }
}
