<?php declare(strict_types=1);

namespace App\Bundle\Example\Service;

/**
 * Import classes
 */
use App\Bundle\Example\Entity\Entry;
use DateTime;
use DateTimeInterface;

/**
 * EntrySerializer
 */
final class EntrySerializer
{

    /**
     * @OpenApi\Schema(
     *   refName="EntryList",
     *   type="array",
     *   items=@OpenApi\SchemaReference(
     *     class="App\Bundle\Example\Service\EntrySerializer",
     *     method="serialize",
     *   ),
     * )
     *
     * @param Entry ...$entries
     *
     * @return array
     */
    public function listSerialize(Entry ...$entries) : array
    {
        $result = [];
        foreach ($entries as $entry) {
            $result[] = $this->serialize($entry);
        }

        return $result;
    }

    /**
     * @OpenApi\Schema(
     *   refName="Entry",
     *   type="object",
     *   properties={
     *     "id": @OpenApi\Schema(
     *       type="string",
     *       format="uuid",
     *       nullable=false,
     *     ),
     *     "name": @OpenApi\SchemaReference(
     *       class="App\Bundle\Example\Entity\Entry",
     *       property="name",
     *     ),
     *     "slug": @OpenApi\SchemaReference(
     *       class="App\Bundle\Example\Entity\Entry",
     *       property="slug",
     *     ),
     *     "createdAt": @OpenApi\Schema(
     *       type="string",
     *       format="date-time",
     *       nullable=false,
     *     ),
     *     "updatedAt": @OpenApi\Schema(
     *       type="string",
     *       format="date-time",
     *       nullable=true,
     *     ),
     *   },
     * )
     *
     * @param Entry $entry
     *
     * @return array
     */
    public function serialize(Entry $entry) : array
    {
        return [
            'id' => $entry->getId(),
            'name' => $entry->getName(),
            'slug' => $entry->getSlug(),
            'createdAt' => $this->dateTimeSerialize($entry->getCreatedAt()),
            'updatedAt' => $this->dateTimeSerialize($entry->getUpdatedAt()),
        ];
    }

    /**
     * Use the nullable operator if the application runned on PHP8 (see below)
     *
     * @param null|DateTimeInterface $dateTime
     *
     * @return null|string
     */
    public function dateTimeSerialize(?DateTimeInterface $dateTime) : ?string
    {
        if (null === $dateTime) {
            return null;
        }

        return $dateTime->format(DateTime::W3C);
    }
}
