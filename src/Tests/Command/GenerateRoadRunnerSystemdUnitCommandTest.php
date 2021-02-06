<?php declare(strict_types=1);

namespace App\Tests\Command;

/**
 * Import classes
 */
use App\Tests\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * GenerateRoadRunnerSystemdUnitCommandTest
 */
class GenerateRoadRunnerSystemdUnitCommandTest extends TestCase
{
    use ContainerAwareTrait;

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testExecute() : void
    {
        $container = $this->getContainer();

        $application = new Application();
        $application->addCommands($container->get('commands'));

        $command = $application->find('app:roadrunner:generate-systemd-unit');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
