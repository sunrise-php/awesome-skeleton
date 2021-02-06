<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseSchemaToolTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\OpenApi\Test\OpenApiAssertKitTrait;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * EntryListControllerTest
 */
class EntryListControllerTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseSchemaToolTrait;
    use OpenApiAssertKitTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'api_v1_entry_list';

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
        $this->assertSame(0, $entryManager->countAll());

        $entryManager->create(['name' => 'foo', 'slug' => 'foo']);
        $entryManager->create(['name' => 'bar', 'slug' => 'bar']);

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry');

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
    public function testListWithEmptyLimitAndOffset() : void
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $entityManager = $doctrine->getManager('master');
        $this->createDatabaseSchema($entityManager);

        $entryManager = $container->get('entryManager');
        $this->assertSame(0, $entryManager->countAll());

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry')
            ->withQueryParams([
                'limit' => '',
                'offset' => '',
            ]);

        $route = $container->get('router')->getRoute(self::ROUTE_NAME);
        $response = $route->handle($request);

        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @param mixed $limit
     *
     * @return void
     *
     * @dataProvider invalidLimitProvider
     *
     * @runInSeparateProcess
     */
    public function testListWithInvalidLimit($limit) : void
    {
        $container = $this->getContainer();

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry')
            ->withQueryParams([
                'limit' => $limit,
            ]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }

    /**
     * @return array
     */
    public function invalidLimitProvider() : array
    {
        return [
            [null],
            [[]],
            ['-1'],
            ['0'],
            ['foo'],
        ];
    }

    /**
     * @param mixed $offset
     *
     * @return void
     *
     * @dataProvider invalidOffsetProvider
     *
     * @runInSeparateProcess
     */
    public function testListWithInvalidOffset($offset) : void
    {
        $container = $this->getContainer();

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry')
            ->withQueryParams([
                'offset' => $offset,
            ]);

        $this->expectException(BadRequestException::class);

        $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);
    }

    /**
     * @return array
     */
    public function invalidOffsetProvider() : array
    {
        return [
            [null],
            [[]],
            ['-1'],
            ['foo'],
        ];
    }
}
