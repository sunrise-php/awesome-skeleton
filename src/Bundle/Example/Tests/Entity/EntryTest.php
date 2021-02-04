<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Entity;

/**
 * Import classes
 */
use App\Bundle\Example\Entity\Entry;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use DateTimeInterface;

/**
 * EntryTest
 */
class EntryTest extends TestCase
{

    /**
     * @return void
     */
    public function testConstructor() : void
    {
        $entry = new Entry();
        $this->assertInstanceOf(UuidInterface::class, $entry->getId());
        $this->assertSame('', $entry->getName());
        $this->assertSame('', $entry->getSlug());
        $this->assertNull($entry->getCreatedAt());
        $this->assertNull($entry->getUpdatedAt());
    }

    /**
     * @return void
     */
    public function testName() : void
    {
        $entry = new Entry();
        $this->assertSame('', $entry->getName());
        $entry->setName('foo');
        $this->assertSame('foo', $entry->getName());
    }

    /**
     * @return void
     */
    public function testSlug() : void
    {
        $entry = new Entry();
        $this->assertSame('', $entry->getSlug());
        $entry->setSlug('foo');
        $this->assertSame('foo', $entry->getSlug());
    }

    /**
     * @return void
     */
    public function testEvents() : void
    {
        $entry = new Entry();
        $this->assertNull($entry->getCreatedAt());
        $this->assertNull($entry->getUpdatedAt());

        $entry = new Entry();
        $entry->prePersist();
        $this->assertInstanceOf(DateTimeInterface::class, $entry->getCreatedAt());
        $this->assertNull($entry->getUpdatedAt());

        $entry = new Entry();
        $entry->preUpdate();
        $this->assertNull($entry->getCreatedAt());
        $this->assertInstanceOf(DateTimeInterface::class, $entry->getUpdatedAt());
    }
}
