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
}
