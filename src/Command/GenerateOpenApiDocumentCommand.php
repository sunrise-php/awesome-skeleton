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
use const JSON_PRETTY_PRINT;
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

        $this->addOption('pretty');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $mode = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        if ($input->getOption('pretty')) {
            $mode |= JSON_PRETTY_PRINT;
        }

        $output->writeln(json_encode($this->container->get('openapi')->toArray(), $mode));

        return 0;
    }
}