<?php declare(strict_types=1);

namespace App\Tests;

/**
 * Import classes
 */
use JsonSchema\Validator;
use Psr\Http\Message\ResponseInterface;
use Sunrise\Http\Router\OpenApi\Utility\JsonSchemaBuilder;
use ReflectionClass;

/**
 * Import functions
 */
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function explode;

/**
 * Import constants
 */
use const JSON_ERROR_NONE;

/**
 * ResponseBodyValidationTestCase
 */
trait ResponseBodyValidationTestCase
{

    /**
     * @param int $expectedStatus
     * @param string $expectedMediaType
     * @param string $operationSource
     * @param ResponseInterface $response
     *
     * @return void
     */
    private function assertValidResponseBody(
        int $expectedStatus,
        string $expectedMediaType,
        string $operationSource,
        ResponseInterface $response
    ) : void {
        $status = $response->getStatusCode();
        $this->assertSame($expectedStatus, $status);

        $mediaType = explode(';', $response->getHeaderLine('Content-Type'), 2)[0];
        $this->assertSame($expectedMediaType, $mediaType);

        $body = (string) $response->getBody();
        $this->assertNotSame('', $body);

        $payload = json_decode($body);
        $this->assertSame(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());

        $jsonSchemaBuilder = new JsonSchemaBuilder(new ReflectionClass($operationSource));
        $jsonSchema = $jsonSchemaBuilder->forResponseBody($status, $mediaType);
        $this->assertNotNull($jsonSchema);

        $validator = new Validator();
        $validator->validate($payload, $jsonSchema);

        $this->assertTrue($validator->isValid(), print_r($validator->getErrors(), true));
    }
}
