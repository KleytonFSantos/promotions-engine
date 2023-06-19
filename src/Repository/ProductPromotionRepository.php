<?php

namespace App\Repository;

use App\Entity\ProductPromotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductPromotion>28\",\"from\":\"2022-11-25"}'), (2, 200);
 *
 * @method ProductPromotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPromotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPromotion[]    findAll()
 * @method ProductPromotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPromotion::class);
    }

    public function save(ProductPromotion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductPromotion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
