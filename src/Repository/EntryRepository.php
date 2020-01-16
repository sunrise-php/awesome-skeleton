<?php declare(strict_types=1);

namespace App\Repository;

/**
 * Import classes
 */
use App\Entity\Entry;
use Doctrine\ORM\EntityRepository;

/**
 * Import functions
 */
use function current;
use function sprintf;

/**
 * EntryRepository
 */
final class EntryRepository extends EntityRepository
{

    /**
     * Gets the numbers of entries
     *
     * @return int
     */
    public function countAll() : int
    {
        $dql = sprintf('select count(t.id) from %s t', Entry::class);

        $query = $this->getEntityManager()
            ->createQuery($dql);

        return (int) $query->getSingleScalarResult();
    }

    /**
     * Gets all entries
     *
     * @return Entry[]
     */
    public function getAll() : array
    {
        $dql = sprintf('select t from %s t', Entry::class);

        $query = $this->getEntityManager()
            ->createQuery($dql);

        return $query->getResult();
    }

    /**
     * Finds entries by the given ID(s)
     *
     * @param int ...$ids
     *
     * @return Entry[]
     */
    public function findByIds(int ...$ids) : array
    {
        $dql = sprintf('select t from %s t where t.id in (:ids)', Entry::class);

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('ids', $ids);

        return $query->getResult();
    }

    /**
     * Finds an entry by the given ID
     *
     * @param int $id
     *
     * @return null|Entry
     */
    public function findById(int $id) : ?Entry
    {
        $dql = sprintf('select t from %s t where t.id = :id', Entry::class);

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('id', $id)
            ->setMaxResults(1);

        $result = $query->getResult();

        if (empty($result)) {
            return null;
        }

        return current($result);
    }
}
