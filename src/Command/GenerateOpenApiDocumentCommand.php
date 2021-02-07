<?php declare(strict_types=1);

namespace App\Command;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Import functions
 */
use function json_encode;

/**
 * Import constants
 */
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

/**
 * GenerateOpenApiDocumentCommand
 */
final class GenerateOpenApiDocumentCommand extends Command
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure() : void
    {
        $this->setName('app:openapi:generate-document');
        $this->setDescription('Generates OpenApi document');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $flags = JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE;

        $output->writeln(json_encode($this->container->get('openapi')->toArray(), $flags));

        return 0;
    }
}
