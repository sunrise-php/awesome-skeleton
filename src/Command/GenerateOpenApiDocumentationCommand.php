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
 * GenerateOpenApiDocumentationCommand
 */
final class GenerateOpenApiDocumentationCommand extends Command
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure() : void
    {
        $this->setName('app:openapi:generate-documentation');
        $this->setDescription('Generates OpenApi documentation');

        $this->addOption('pretty', null, null, 'Use whitespace in returned data to format it');
        $this->addOption('unescaped-slashes', null, null, 'Do not escape /');
        $this->addOption('unescaped-unicode', null, null, 'Encode multi-byte Unicode characters literally');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $mode = 0;

        if ($input->getOption('pretty')) {
            $mode |= JSON_PRETTY_PRINT;
        }

        if ($input->getOption('unescaped-slashes')) {
            $mode |= JSON_UNESCAPED_SLASHES;
        }

        if ($input->getOption('unescaped-unicode')) {
            $mode |= JSON_UNESCAPED_UNICODE;
        }

        $openapi = $this->container->get('openapi');

        $output->writeln(json_encode($openapi->toArray(), $mode));

        return 0;
    }
}
