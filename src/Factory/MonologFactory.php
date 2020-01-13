<?php declare(strict_types=1);

namespace App\Factory;

/**
 * Import classes
 */
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * MonologFactory
 *
 * Don't use this factory outside the container!
 */
final class MonologFactory
{

    /**
     * Creates Monolog Logger instance
     *
     * @param array $params
     *
     * @return LoggerInterface
     */
    public function createLogger(array $params) : LoggerInterface
    {
        return new Logger(
            $params['name'],
            $params['handlers'],
            $params['processors']
        );
    }
}
