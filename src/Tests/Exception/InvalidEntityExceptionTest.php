<?php declare(strict_types=1);

namespace App\Tests\Exception;

/**
 * Import classes
 */
use App\Exception\InvalidEntityException;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\Exception\BadRequestException;

/**
 * InvalidEntityExceptionTest
 */
class InvalidEntityExceptionTest extends TestCase
{

    /**
     * @return void
     */
    public function testConstructor() : void
    {
        $exception = new InvalidEntityException();
        $this->assertInstanceOf(BadRequestException::class, $exception);
    }
}
