<?php declare(strict_types=1);

namespace App\Entity;

/**
 * @OpenApi\Schema(
 *   refName="Post",
 *   type="object",
 *   properties={
 *     "id": @OpenApi\Schema(
 *       type="integer",
 *       format="int32",
 *     ),
 *     "title": @OpenApi\Schema(
 *       type="string",
 *     ),
 *     "description": @OpenApi\Schema(
 *       type="string",
 *     ),
 *   },
 * )
 */
class Post
{
}
