<?php declare(strict_types=1);

namespace App\Service;

/**
 * Import classes
 */
use App\Entity\Entry;
use App\ContainerAwareTrait;

/**
 * EntryService
 */
final class EntryService
{
    use ContainerAwareTrait;

    /**
     * Gets all entries
     *
     * @return array
     */
    public function getAll() : array
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);

        $result = [];
        foreach ($repository->getAll() as $entry) {
            $item = [
                'id' => $entry->getId(),
                'name' => $entry->getName(),
            ];

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Creates an entry via the given data
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $entry = new Entry();
        $entry->setName($data['name']);

        $manager = $this->container->get('entityManager');
        $manager->persist($entry);
        $manager->flush();
    }
}
