<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function save(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Animal[] Returns an array of Animal objects
     */
    public function findAnimaisParaAbate(): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->where('a.leite < 40')
            ->orWhere('a.nascimento < :date')
            ->orwhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->lt('a.leite ', ' 70'),
                    $queryBuilder->expr()->gt('a.racao/7', '50'),
                )
            )
            ->orWhere('a.peso/15 > 18')
            ->andwhere('a.status = true')
            ->setParameter('date', date_sub(date_create(date('Y-m-d')), date_interval_create_from_date_string("5 years")))
            ->orderBy('a.codigo', 'ASC');

        $query = $queryBuilder->getQuery();
        //dd($query);
        $results = $query->getResult();
        return $results;
    }

    /**
     * @return Animal[] Returns an array of Animal objects
     */
    public function findAnimaisAbatidos(): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->where('a.status = false')
            ->orderBy('a.codigo', 'ASC');


        $query = $queryBuilder->getQuery();
        //dd($query);
        $results = $query->getResult();
        return $results;
    }

    /**
     * @return string Returns an array of Animal objects
     */
    public function totalLeite(): string
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('SUM(a.leite) AS totalLeite')
            ->where('a.status = true');


        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results[0]['totalLeite'] ? $results[0]['totalLeite'] : '0';
    }


    /**
     * @return string Returns an array of Animal objects
     */
    public function racaoNecessaria(): string
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('SUM(a.racao) AS racaoNecessaria')
            ->where('a.status = true');


        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results[0]['racaoNecessaria'] ? $results[0]['racaoNecessaria'] : '0';
    }


    /**
     * @return string Returns an array of Animal objects
     */
    public function animaisUmAno(): string
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('COUNT(a.id) AS  animaisUmAno')
            ->where(':date <= a.nascimento')
            ->andwhere('a.racao > 500')
            ->andwhere('a.status = true')
            ->setParameter('date', date_sub(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 years")));


        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results[0]['animaisUmAno'] ? $results[0]['animaisUmAno'] : '0';
    }

    //    /**
    //     * @return Animal[] Returns an array of Animal objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Animal
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
