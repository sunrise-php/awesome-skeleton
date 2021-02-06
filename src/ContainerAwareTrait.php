<?php declare(strict_types=1);

namespace App;

/**
 * Import classes
 */
use Psr\Container\ContainerInterface;

/**
 * ContainerAwareTrait
 */
trait ContainerAwareTrait
{

    /**
     * The application container
     *
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Gets the application container
     *
     * @return ContainerInterface
     *
     * @codeCoverageIgnore
     */
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }
}
