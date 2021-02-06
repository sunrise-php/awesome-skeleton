<?php declare(strict_types=1);

namespace App\Tests\Exception;

/**
 * Import classes
 */
use App\Exception\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\Router\Exception\PageNotFoundException;

/**
 * EntityNotFoundExceptionTest
 */
class EntityNotFoundExceptionTest extends TestCase
{

    /**
     * @return void
     */
    public function testConstructor() : void
    {
        $exception = new EntityNotFoundException();
        $this->assertInstanceOf(PageNotFoundException::class, $exception);
    }
}
