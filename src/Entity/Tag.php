<?php declare(strict_types=1);

namespace App\Entity;

/**
 * @OpenApi\Schema(
 *   refName="Tag",
 *   type="object",
 *   properties={
 *     "id": @OpenApi\SchemaReference(
 *       class="App\Entity\Tag",
 *       property="id",
 *     ),
 *     "name": @OpenApi\SchemaReference(
 *       class="App\Entity\Tag",
 *       property="name",
 *     ),
 *   },
 * )
 */
final class Tag
{

    /**
     * @OpenApi\Schema(
     *   type="integer",
     *   format="int32",
     * )
     */
    private $id;

    /**
     * @OpenApi\Schema(
     *   type="string",
     * )
     */
    private $name;
}
