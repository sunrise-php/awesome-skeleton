<?php declare(strict_types=1);

namespace App\Bundle\Example\Service;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidEntityException;
use App\Bundle\Example\Entity\Entry;

/**
 * EntryManager
 */
final class EntryManager
{
    use ContainerAwareTrait;

    /**
     * Counts all entries
     *
     * @return int
     */
    public function countAll() : int
    {
        return $this->container->get('doctrine')
            ->getManager('slave')
            ->getRepository(Entry::class)
            ->countAll();
    }

    /**
     * Gets the list of entries
     *
     * @param null|int $limit
     * @param null|int $offset
     *
     * @return Entry[]
     */
    public function getList(?int $limit, ?int $offset) : array
    {
        return $this->container->get('doctrine')
            ->getManager('slave')
            ->getRepository(Entry::class)
            ->getList($limit, $offset);
    }

    /**
     * Finds an entry by the given ID
     *
     * Please note that an entry will be read from the master.
     *
     * Throws an exception if an entry wasn't found.
     *
     * @param string $id
     *
     * @return Entry
     *
     * @throws EntityNotFoundException
     */
    public function findById(string $id) : Entry
    {
        $entry = $this->container->get('doctrine')
            ->getManager('master')
            ->getRepository(Entry::class)
            ->find($id);

        if (! ($entry instanceof Entry)) {
            throw new EntityNotFoundException();
        }

        return $entry;
    }

    /**
     * Creates a new entry from the given data
     *
     * Throws an exception if the given data isn't valid.
     *
     * @param array $data
     *
     * @return Entry
     *
     * @throws InvalidEntityException
     */
    public function create(array $data) : Entry
    {
        $doctrine = $this->container->get('doctrine');
        $validator = $this->container->get('validator');

        // inits a new entry and maps the given data to it...
        $entry = $doctrine->getHydrator()->hydrate(Entry::class, $data);
        // validates the inited entry...
        InvalidEntityException::assert($entry, $validator);

        $manager = $doctrine->getManager('master');
        $manager->persist($entry);
        $manager->flush();

        return $entry;
    }

    /**
     * Updates the given entry from the given data
     *
     * Throws an exception if the given data isn't valid.
     *
     * @param Entry $entry
     * @param array $data
     *
     * @return void
     *
     * @throws InvalidEntityException
     */
    public function update(Entry $entry, array $data) : void
    {
        $doctrine = $this->container->get('doctrine');
        $validator = $this->container->get('validator');

        // to avoid serious problems re-reads the given entry from the master...
        $entry = $this->findById((string) $entry->getId());
        // maps the given data to the given entry...
        $doctrine->getHydrator()->hydrate($entry, $data);
        // validates the given entry...
        InvalidEntityException::assert($entry, $validator);

        $manager = $doctrine->getManager('master');
        $manager->flush();
    }

    /**
     * Deletes the given entry
     *
     * @param Entry $entry
     *
     * @return void
     */
    public function delete(Entry $entry) : void
    {
        $doctrine = $this->container->get('doctrine');

        // to avoid serious problems re-reads the given entry from the master...
        $entry = $this->findById((string) $entry->getId());

        $manager = $doctrine->getManager('master');
        $manager->remove($entry);
        $manager->flush();
    }
}
