<?php declare(strict_types=1);

namespace App\Repository;

/**
 * Import classes
 */
use App\Domain\EntryInterface;
use App\Entity\Entry;
use Doctrine\ORM\EntityRepository;

/**
 * Import functions
 */
use function sprintf;

/**
 * EntryRepository
 */
final class EntryRepository extends EntityRepository
{

    /**
     * Gets all entries from the storage
     *
     * @return EntryInterface[]
     */
    public function getAll() : array
    {
        $dql = sprintf('select t from %s t', Entry::class);

        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }
}
