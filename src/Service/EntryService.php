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
     * Checks if an entry exists by the given ID
     *
     * @param int $id
     *
     * @return bool
     */
    public function exists(int $id) : bool
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entry = $repository->findById($id);

        return isset($entry);
    }

    /**
     * Read an entry by the given ID
     *
     * @param int $id
     *
     * @return array
     */
    public function read(int $id) : array
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entry = $repository->findById($id);

        if (null === $entry) {
            return null;
        }

        return [
            'id' => $entry->getId(),
            'name' => $entry->getName(),
        ];
    }

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
     * @return void
     */
    public function create(array $data) : void
    {
        $manager = $this->container->get('entityManager');

        $entry = new Entry();
        $entry->setName($data['name']);

        $manager->persist($entry);
        $manager->flush();
    }

    /**
     * Updates an entry by the given ID via the given data
     *
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function update(int $id, array $data) : bool
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);

    }
}
