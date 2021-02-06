<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Exception\InvalidEntityException;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseSchemaToolTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\OpenApi\Test\OpenApiAssertKitTrait;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * EntryCreateControllerTest
 */
class EntryCreateControllerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;
    use OpenApiAssertKitTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'api_v1_entry_create';

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
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'foo',
                'slug' => 'bar',
            ]);

        $route = $container->get('router')->getRoute(self::ROUTE_NAME);
        $response = $route->handle($request);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertResponseBodyMatchesDescription($route, $response);
        $this->assertSame(1, $entryManager->countAll());

        $entries = $entryManager->getList(null, null);
        $this->assertSame('foo', $entries[0]->getName());
        $this->assertSame('bar', $entries[0]->getSlug());
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
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => '',
                'slug' => 'bar',
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
    public function testCreateWithEmptySlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'foo',
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
    public function testCreateWithNotUniqueSlug() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        // reserving the `foo` slug...
        $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'foo',
                'slug' => 'foo',
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
    public function testCreateWithInvalidPayload() : void
    {
        $container = $this->getContainer();

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([null]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }
}
