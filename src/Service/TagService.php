<?php declare(strict_types=1);

namespace App\Service;

/**
 * TagService
 */
final class TagService
{

    /**
     * @OpenApi\Schema(
     *   type="object",
     *   required={
     *     "name",
     *   },
     *   properties={
     *     "name"=@OpenApi\Schema(
     *       type="string",
     *       minLength=3,
     *       maxLength=128,
     *     ),
     *   },
     * )
     */
    public function create()
    {
    }

    /**
     * @OpenApi\Schema(
     *   type="array",
     *   minItems=1,
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
