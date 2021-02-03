<?php declare(strict_types=1);

namespace App\Bundle\Example\Repository;

/**
 * Import classes
 */
use App\Bundle\Example\Entity\Entry;
use Doctrine\ORM\EntityRepository;

/**
 * EntryRepository
 */
final class EntryRepository extends EntityRepository
{

    /**
     * Counts all entries
     *
     * @return int
     */
    public function countAll() : int
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb->select('count(entry.id)')
            ->from(Entry::class, 'entry');

        return (int) $qb->getQuery()
            ->getSingleScalarResult();
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
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb->select('entry')
            ->from(Entry::class, 'entry')
            ->orderBy('entry.createdAt', 'DESC');

        return $qb->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }
}
