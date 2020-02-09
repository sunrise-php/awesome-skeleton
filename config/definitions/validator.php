<?php declare(strict_types=1);

use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

use function DI\factory;

return [
    'validator' => factory(function ($container) {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setConstraintValidatorFactory(
                new ContainerConstraintValidatorFactory($container)
            )
        ->getValidator();
    }),
];
