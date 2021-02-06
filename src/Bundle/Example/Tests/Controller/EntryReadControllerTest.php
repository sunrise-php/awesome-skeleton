<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Exception\EntityNotFoundException;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseSchemaToolTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\OpenApi\Test\OpenApiAssertKitTrait;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * EntryReadControllerTest
 */
class EntryReadControllerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;
    use OpenApiAssertKitTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'api_v1_entry_read';

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testRead() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $entry = $entryManager->create(['name' => 'foo', 'slug' => 'foo']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry/' . $entry->getId()->toString())
            ->withAttribute('entryId', $entry->getId()->toString());

        $route = $container->get('router')->getRoute(self::ROUTE_NAME);
        $response = $route->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertResponseBodyMatchesDescription($route, $response);
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testReadNonExistentEntry() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry/6483341b-2ddb-43f0-8fc0-1c45ef9e31d8')
            ->withAttribute('entryId', '6483341b-2ddb-43f0-8fc0-1c45ef9e31d8');

        $this->expectException(EntityNotFoundException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }
}
