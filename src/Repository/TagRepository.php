<?php declare(strict_types=1);

namespace App\Repository;

/**
 * TagRepository
 */
final class TagRepository
{

    /**
     * @OpenApi\Schema(
     *   type="array",
     *   items=@OpenApi\SchemaReference(
     *     class="App\Entity\Tag",
     *   ),
     * )
     */
    public function list()
    {
        return [
            [
                'id' => 1,
                'name' => 'foo',
            ],
            [
                'id' => 2,
                'name' => 'bar',
            ],
            [
                'id' => 3,
                'name' => 'baz',
            ],
        ];
    }
}
