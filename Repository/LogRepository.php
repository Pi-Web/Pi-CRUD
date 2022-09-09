<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PiWeb\PiCRUD\Entity\Log;

/**
 * Class LogRepository
 * @package PiWeb\PiCRUD\Repository
 */
class LogRepository extends ServiceEntityRepository
{
    /**
     * EventRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function clean(): void
    {
        $queryBuilder = $this->createQueryBuilder('log');
        $queryBuilder
            ->delete(Log::class, 'log')
            ->where('log.createAt > :date')
            ->setParameter('date', new DateTime('-1 week'))
            ->getQuery()
            ->execute();
    }
}
