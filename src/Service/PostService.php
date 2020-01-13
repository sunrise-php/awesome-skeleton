<?php declare(strict_types=1);

namespace App\Service;

/**
 * PostService
 */
class PostService
{

    /**
     * @OpenApi\Schema(
     *   type="array",
     *   items=@OpenApi\Schema(
     *     type="string",
     *   ),
     * )
     *
     * @var array
     */
    private $tags;

    /**
     * @OpenApi\Schema(
     *   type="object",
     *   required={
     *     "title",
     *     "description",
     *   },
     *   properties={
     *     "title"=@OpenApi\Schema(
     *       type="string",
     *     ),
     *     "description"=@OpenApi\Schema(
     *       type="string",
     *     ),
     *     "tags"=@OpenApi\SchemaReference(
     *       class="App\Service\PostService",
     *       property="tags",
     *     ),
     *   },
     * )
     *
     * @return void
     */
    public function createPost() : void
    {
    }
}
