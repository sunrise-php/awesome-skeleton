<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Exception\EntityNotFoundException;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseSchemaToolTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * EntryDeleteControllerTest
 */
class EntryDeleteControllerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'api_v1_entry_delete';

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
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $this->assertSame(1, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('DELETE', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString());

        $response = $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(0, $entryManager->countAll());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testDeleteNonExistentEntry() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('DELETE', '/api/v1/entry/3466e003-6191-48c7-bb3a-64786587b8a1')
            ->withAttribute('entryId', '3466e003-6191-48c7-bb3a-64786587b8a1');

        $this->expectException(EntityNotFoundException::class);

        $response = $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }
}
