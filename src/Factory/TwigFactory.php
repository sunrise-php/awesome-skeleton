<?php declare(strict_types=1);

namespace App\Factory;

/**
 * Import classes
 */
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * TwigFactory
 *
 * Don't use this factory outside the container!
 */
final class TwigFactory
{

    /**
     * Creates Twig Environment instance
     *
     * @param array $params
     *
     * @return Environment
     */
    public function createEnvironment(array $params) : Environment
    {
        $loader = new FilesystemLoader($params['loader']['paths']);

        return new Environment($loader);
    }
}
