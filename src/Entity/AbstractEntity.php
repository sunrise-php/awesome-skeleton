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
		if (empty($this->validator))
		{
			$builder = Validation::createValidatorBuilder();

			$builder->enableAnnotationMapping();

			$this->validator = $builder->getValidator();
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
		$validator = $this->getValidator();

		return $validator->validate($this);
	}
}
