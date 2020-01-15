<?php declare(strict_types=1);

namespace App\Tests;

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
     * Gets the application container
     *
     * @return ContainerInterface
     */
    private function getContainer() : ContainerInterface
    {
        return require __DIR__ . '/../config/container.php';
    }
}
