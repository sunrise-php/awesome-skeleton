<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidEntityException;
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

        $entryManager->create(['name' => 'bar', 'slug' => 'bar']);
        $this->assertSame(2, $entryManager->countAll());
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
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $entries = $entryManager->getList(null, null);
        $this->assertSame([], $entries);

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $entries = $entryManager->getList(null, null);
        $this->assertCount(1, $entries);
        $this->assertSame($foo->getId()->toString(), (string) $entries[0]->getId());

        $bar = $entryManager->create(['name' => 'bar', 'slug' => 'bar']);
        $entries = $entryManager->getList(null, null);
        $this->assertCount(2, $entries);
        $this->assertSame($bar->getId()->toString(), (string) $entries[0]->getId());
        $this->assertSame($foo->getId()->toString(), (string) $entries[1]->getId());

        $entries = $entryManager->getList(1, null);
        $this->assertCount(1, $entries);
        $this->assertSame($bar->getId()->toString(), (string) $entries[0]->getId());

        $entries = $entryManager->getList(null, 1);
        $this->assertCount(1, $entries);
        $this->assertSame($foo->getId()->toString(), (string) $entries[0]->getId());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testFindById() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $bar = $entryManager->create(['name' => 'bar', 'slug' => 'bar']);

        $found = $entryManager->findById($foo->getId()->toString());
        $this->assertSame($foo->getId()->toString(), (string) $found->getId());

        $found = $entryManager->findById($bar->getId()->toString());
        $this->assertSame($bar->getId()->toString(), (string) $found->getId());

        $this->expectException(EntityNotFoundException::class);
        $entryManager->findById('5b640dde-3503-444d-a234-b97b2752750b');
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testCreate() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $found = $entryManager->findById($foo->getId()->toString());
        $this->assertSame('foo', $found->getName());
        $this->assertSame('foo', $found->getSlug());

        $bar = $entryManager->create(['name' => 'bar', 'slug' => 'bar']);
        $found = $entryManager->findById($bar->getId()->toString());
        $this->assertSame('bar', $found->getName());
        $this->assertSame('bar', $found->getSlug());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testCreateWithEmptyName() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $this->expectException(InvalidEntityException::class);
        $entryManager->create(['name' => '', 'slug' => 'foo']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testCreateWithEmptySlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $this->expectException(InvalidEntityException::class);
        $entryManager->create(['name' => 'foo', 'slug' => '']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testCreateWithNotUniqueSlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $this->expectException(InvalidEntityException::class);
        $entryManager->create(['name' => 'bar', 'slug' => 'foo']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdate() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $entryManager->update($foo, ['name' => 'bar', 'slug' => 'bar']);
        $found = $entryManager->findById($foo->getId()->toString());

        $this->assertSame('bar', $found->getName());
        $this->assertSame('bar', $found->getSlug());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdateWithEmptyName() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $this->expectException(InvalidEntityException::class);
        $entryManager->update($foo, ['name' => '', 'slug' => 'bar']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdateWithEmptySlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $this->expectException(InvalidEntityException::class);
        $entryManager->update($foo, ['name' => 'bar', 'slug' => '']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdateWithNotUniqueSlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $bar = $entryManager->create(['name' => 'bar', 'slug' => 'bar']);

        $this->expectException(InvalidEntityException::class);
        $entryManager->update($foo, ['name' => 'baz', 'slug' => 'bar']);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testDelete() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        // the database MUST be empty...
        $this->assertSame(0, $entryManager->countAll());

        $foo = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $entryManager->delete($foo);

        $this->expectException(EntityNotFoundException::class);
        $entryManager->findById($foo->getId()->toString());
    }
}
