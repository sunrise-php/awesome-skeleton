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
     * Gets the numbers of entries
     *
     * @return int
     */
    public function countAll() : int
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);

        return $repository->countAll();
    }

    /**
     * Creates an entry
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
     * Multiple creation of entries
     *
     * @param array ...$variety
     *
     * @return void
     */
    public function multipleCreate(array ...$variety) : void
    {
        $manager = $this->container->get('entityManager');

        foreach ($variety as $data) {
            $entry = new Entry();
            $entry->setName($data['name']);

            $manager->persist($entry);
        }

        $manager->flush();
    }

    /**
     * Updates an entry by the given ID
     *
     * @param int $id
     * @param array $data
     *
     * @return void
     */
    public function updateById(int $id, array $data) : void
    {
        if (empty($data)) {
            return;
        }

        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entry = $repository->findById($id);

        if (empty($entry)) {
            return;
        }

        if (isset($data['name'])) {
            $entry->setName($data['name']);
        }

        $manager->flush();
    }

    /**
     * Deletes an entry by the given ID
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteById(int $id) : void
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entry = $repository->findById($id);

        if (empty($entry)) {
            return;
        }

        $manager->remove($entry);
        $manager->flush();
    }

    /**
     * Multiple deletion of entries
     *
     * @param int ...$ids
     *
     * @return void
     */
    public function multipleDelete(int ...$ids) : void
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entries = $repository->findByIds(...$ids);

        if (empty($entries)) {
            return;
        }

        foreach ($entries as $entry) {
            $manager->remove($entry);
        }

        $manager->flush();
    }

    /**
     * Checks if an entry exists by the given ID
     *
     * @param int $id
     *
     * @return bool
     */
    public function existsById(int $id) : bool
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
     * @return null|array
     */
    public function readById(int $id) : ?array
    {
        $manager = $this->container->get('entityManager');
        $repository = $manager->getRepository(Entry::class);
        $entry = $repository->findById($id);

        if (empty($entry)) {
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
}
