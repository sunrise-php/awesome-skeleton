<?php declare(strict_types=1);

namespace App\Doctrine;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Doctrine\DBAL\Logging\DebugStack;

/**
 * Import functions
 */
use function array_pop;
use function sprintf;

/**
 * QueryLogger
 */
final class QueryLogger extends DebugStack
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function stopQuery()
    {
        parent::stopQuery();

        $report = array_pop($this->queries);

        $this->container->get('logger')->debug(sprintf(
            '[%2.3fµ] %s',
            $report['executionMS'] * 1000,
            $report['sql']
        ), [
            'params' => $report['params'],
            'types' => $report['types'],
        ]);
    }
}
