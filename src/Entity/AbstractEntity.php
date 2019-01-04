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
	 * The entity loadable properties
	 *
	 * @var string[]
	 */
	protected $loadableProperties = [];

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

		$this->preValidate();

		return $validator->validate($this);
	}

	/**
	 * Fills the entity properties from the given array
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function fromArray($data) : void
	{
		if (! \is_array($data)) {
			return;
		}

		foreach ($data as $key => $value)
		{
			if (! \is_string($key)) {
				continue;
			}
			if (! \property_exists($this, $key)) {
				continue;
			}
			if (! \in_array($key, $this->loadableProperties)) {
				continue;
			}

			$this->$key = $value;
		}
	}

	/**
	 * @return void
	 */
	public function preValidate()
	{}
}
