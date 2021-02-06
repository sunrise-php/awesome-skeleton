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
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\OpenApi\Test\OpenApiAssertKitTrait;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * EntryUpdateControllerTest
 */
class EntryUpdateControllerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;
    use OpenApiAssertKitTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'api_v1_entry_update';

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
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString())
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
                'slug' => 'baz',
            ]);

        $route = $container->get('router')->getRoute(self::ROUTE_NAME);
        $response = $route->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertResponseBodyMatchesDescription($route, $response);

        // re-loading the entry...
        $entry = $entryManager->findById($entry->getId()->toString());

        $this->assertSame('bar', $entry->getName());
        $this->assertSame('baz', $entry->getSlug());
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdateNonExistentEntry() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/e3f4f8bd-d455-4e67-86d5-ab6c9683bdd7')
            ->withAttribute('entryId', 'e3f4f8bd-d455-4e67-86d5-ab6c9683bdd7')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
                'slug' => 'bar',
            ]);

        $this->expectException(EntityNotFoundException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
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
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString())
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => '',
                'slug' => 'baz',
            ]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
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
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString())
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
                'slug' => '',
            ]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
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
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $entryManager->create(['name' => 'bar', 'slug' => 'bar']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString())
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
                'slug' => 'bar',
            ]);

        $this->expectException(InvalidEntityException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testUpdateWithInvalidPayload() : void
    {
        $container = $this->getContainer();

        $request = (new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/e3f4f8bd-d455-4e67-86d5-ab6c9683bdd7')
            ->withAttribute('entryId', 'e3f4f8bd-d455-4e67-86d5-ab6c9683bdd7')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([null]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }
}
