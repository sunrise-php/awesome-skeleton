<?php declare(strict_types=1);

namespace App\Command;

/**
 * Import classes
 */
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Import functions
 */
use function sprintf;
use function strtr;
use function trim;

/**
 * GenerateRoadRunnerSystemdUnitCommand
 *
 * @link https://wiki.debian.org/systemd/Services
 * @link https://manpages.debian.org/buster/systemd/systemd.service.5.en.html
 */
final class GenerateRoadRunnerSystemdUnitCommand extends Command
{

    /**
     * @var string
     */
    private const TEMPLATE = <<<'EOT'
[Unit]
After=network.target

[Service]
Type=simple
User={user}
Group={group}
Restart=always
WorkingDirectory={cwd}
ExecStart={rr} serve -dv
ExecReload={rr} http:reset
ExecStop=/bin/kill -s TERM $MAINPID

[Install]
WantedBy=multi-user.target
EOT;

    /**
     * {@inheritDoc}
     */
    protected function configure() : void
    {
        $this->setName('app:roadrunner:generate-systemd-unit');
        $this->setDescription('Generates the systemd unit for RoadRunner');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $rr = trim(`which rr`);
        $cwd = trim(`pwd`);
        $user = trim(`id -u -n`);
        $group = trim(`id -g -n`);
        $questioner = $this->getHelper('question');
        $replacements = [];

        $format = 'RR path [<fg=yellow>%s</>]: ';
        $question = new Question(sprintf($format, $rr), $rr);
        $replacements['{rr}'] = $questioner->ask($input, $output, $question);

        $format = 'App root [<fg=yellow>%s</>]: ';
        $question = new Question(sprintf($format, $cwd), $cwd);
        $replacements['{cwd}'] = $questioner->ask($input, $output, $question);

        $format = 'User name [<fg=yellow>%s</>]: ';
        $question = new Question(sprintf($format, $user), $user);
        $replacements['{user}'] = $questioner->ask($input, $output, $question);

        $format = 'Group name [<fg=yellow>%s</>]: ';
        $question = new Question(sprintf($format, $group), $group);
        $replacements['{group}'] = $questioner->ask($input, $output, $question);

        $output->writeln(strtr(static::TEMPLATE, $replacements));

        return 0;
    }
}
