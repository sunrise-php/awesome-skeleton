<?php declare(strict_types=1);

namespace App\Tests\Service;

/**
 * Import classes
 */
use App\Tests\ContainerAwareTrait;
use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\OpenApi\Utility\JsonSchemaBuilder;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use ReflectionClass;

/**
 * Import functions
 */
use json_decode;

/**
 * TagServiceTest
 */
class TagServiceTest extends TestCase
{
    use ContainerAwareTrait;

    /**
     * @return void
     */
    public function testList() : void
    {
        $container = $this->getContainer();

        $route = $container->get('router')->getRoute('tag.list');
        $request = (new ServerRequestFactory)->createServerRequest('GET', '/tag');
        $response = $route->handle($request);

        $payload = (string) $response->getBody();
        $payload = json_decode($payload);

        $jsonSchemaBuilder = new JsonSchemaBuilder(new ReflectionClass($route->getRequestHandler()));
        $jsonSchema = $jsonSchemaBuilder->forResponseBody(200, 'application/json');

        $validator = new Validator();
        $validator->validate($payload, $jsonSchema);

        var_dump($validator->getErrors());

        $this->assertTrue($validator->isValid());
    }
}
