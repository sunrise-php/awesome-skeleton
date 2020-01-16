<?php declare(strict_types=1);

namespace App\Tests;

/**
 * Import classes
 */
use JsonSchema\Validator as JsonSchemaValidator;
use Psr\Http\Message\ResponseInterface;
use Sunrise\Http\Router\OpenApi\Utility\JsonSchemaBuilder;
use ReflectionClass;

/**
 * Import functions
 */
use function explode;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;

/**
 * Import constants
 */
use const JSON_ERROR_NONE;
use const PHP_EOL;

/**
 * ResponseBodyValidationTestCaseTrait
 */
trait ResponseBodyValidationTestCaseTrait
{

    /**
     * Checks if the given response body matches the JSON schema
     *
     * @param int $expectedStatus
     * @param string $expectedMediaType
     * @param string $operationSourceClass
     * @param ResponseInterface $response
     *
     * @return void
     */
    private function assertValidResponseBody(
        int $expectedStatus,
        string $expectedMediaType,
        string $operationSourceClass,
        ResponseInterface $response
    ) : void {
        $responseStatus = $response->getStatusCode();
        $this->assertSame(
            $expectedStatus,
            $responseStatus,
            sprintf('Expected status %d, got %d', $expectedStatus, $responseStatus)
        );

        $responseMediaType = explode(';', $response->getHeaderLine('Content-Type'), 2)[0];
        $this->assertSame(
            $expectedMediaType,
            $responseMediaType,
            sprintf('Expected media-type %s, got %s', $expectedMediaType, $responseMediaType)
        );

        $responseBody = (string) $response->getBody();
        $this->assertNotSame(
            '',
            $responseBody,
            'The response body must not be empty'
        );

        $responsePayload = json_decode($responseBody);
        $this->assertSame(
            JSON_ERROR_NONE,
            json_last_error(),
            sprintf('Unexpected body got: %s', json_last_error_msg())
        );

        $jsonSchemaBuilder = new JsonSchemaBuilder(new ReflectionClass($operationSourceClass));
        $jsonSchema = $jsonSchemaBuilder->forResponseBody($responseStatus, $responseMediaType) ??
                      $jsonSchemaBuilder->forResponseBody('default', $responseMediaType);
        $this->assertNotNull(
            $jsonSchema,
            'No JSON schema found'
        );

        $jsonSchemaValidator = new JsonSchemaValidator();
        $jsonSchemaValidator->validate($responsePayload, $jsonSchema);
        $this->assertTrue(
            $jsonSchemaValidator->isValid(),
            'Invalid body got: ' . PHP_EOL . print_r($jsonSchemaValidator->getErrors(), true)
        );
    }
}
