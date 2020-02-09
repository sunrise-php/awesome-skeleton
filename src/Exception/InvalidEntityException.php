<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Import classes
 */
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Import functions
 */
use function get_class;
use function sprintf;

/**
 * InvalidEntityException
 */
final class InvalidEntityException extends AbstractException
{

    /**
     * An invalid entity
     *
     * @var object
     */
    private $invalidEntity;

    /**
     * An entity violations
     *
     * @var ConstraintViolationListInterface
     */
    private $entityViolations;

    /**
     * Constructor of the class
     *
     * @param object $invalidEntity
     * @param ConstraintViolationListInterface $entityViolations
     */
    public function __construct(object $invalidEntity, ConstraintViolationListInterface $entityViolations)
    {
        $this->invalidEntity = $invalidEntity;
        $this->entityViolations = $entityViolations;

        parent::__construct(sprintf('Invalid the entity "%s"', get_class($invalidEntity)));
    }

    /**
     * @return object
     */
    public function getInvalidEntity() : object
    {
        return $this->invalidEntity;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getEntityViolations() : ConstraintViolationListInterface
    {
        return $this->entityViolations;
    }
}
