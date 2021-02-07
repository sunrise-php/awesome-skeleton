<?php declare(strict_types=1);

namespace App\Bundle\Example\Tests\Service;

/**
 * Import classes
 */
use App\Bundle\Example\Entity\Entry;
use App\Tests\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use DateTime;

/**
 * EntrySerializerTest
 */
class EntrySerializerTest extends TestCase
{
    use ContainerAwareTrait;

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testSerializeEmptyObject() : void
    {
        $container = $this->getContainer();
        $entrySerializer = $container->get('entrySerializer');

        $foo = new Entry();
        $this->assertSame([
            'id' => $foo->getId()->toString(),
            'name' => '',
            'slug' => '',
            'createdAt' => null,
            'updatedAt' => null,
        ], $entrySerializer->serialize($foo));
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testSerializeNewObject() : void
    {
        $container = $this->getContainer();
        $entrySerializer = $container->get('entrySerializer');

        $foo = new Entry();
        $foo->setName('foo');
        $foo->setSlug('bar');
        $foo->prePersist();
        $this->assertSame([
            'id' => $foo->getId()->toString(),
            'name' => 'foo',
            'slug' => 'bar',
            'createdAt' => $foo->getCreatedAt()->format(DateTime::W3C),
            'updatedAt' => null,
        ], $entrySerializer->serialize($foo));
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testSerializeUpdatedObject() : void
    {
        $container = $this->getContainer();
        $entrySerializer = $container->get('entrySerializer');

        $foo = new Entry();
        $foo->setName('foo');
        $foo->setSlug('bar');
        $foo->prePersist();
        $foo->preUpdate();
        $this->assertSame([
            'id' => $foo->getId()->toString(),
            'name' => 'foo',
            'slug' => 'bar',
            'createdAt' => $foo->getCreatedAt()->format(DateTime::W3C),
            'updatedAt' => $foo->getUpdatedAt()->format(DateTime::W3C),
        ], $entrySerializer->serialize($foo));
    }

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testSerializeList() : void
    {
        $container = $this->getContainer();
        $entrySerializer = $container->get('entrySerializer');

        $foo = new Entry();
        $foo->setName('foo');
        $foo->setSlug('bar');

        $bar = new Entry();
        $bar->setName('baz');
        $bar->setSlug('qux');

        $this->assertSame([
            [
                'id' => $foo->getId()->toString(),
                'name' => 'foo',
                'slug' => 'bar',
                'createdAt' => null,
                'updatedAt' => null,
            ],
            [
                'id' => $bar->getId()->toString(),
                'name' => 'baz',
                'slug' => 'qux',
                'createdAt' => null,
                'updatedAt' => null,
            ],
        ], $entrySerializer->serializeList($foo, $bar));
    }
}
