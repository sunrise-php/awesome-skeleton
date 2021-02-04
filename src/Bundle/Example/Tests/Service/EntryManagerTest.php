<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseSchemaToolTrait;
use PHPUnit\Framework\TestCase;

/**
 * EntryManagerTest
 */
class EntryManagerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testCountAll() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $this->assertSame(1, $entryManager->countAll());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testList() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame([], $entryManager->getList(null, null));

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $bar = $entryManager->create(['name' => 'bar', 'slug' => 'bar']);

        $entries = $entryManager->getList(null, null);
        $this->assertCount(2, $entries);
        $this->assertSame($foo->getId()->toString(), (string) $entries[1]->getId());
        $this->assertSame($bar->getId()->toString(), (string) $entries[0]->getId());
    }
}
