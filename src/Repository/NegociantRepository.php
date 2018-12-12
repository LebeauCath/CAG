<?php

namespace App\Repository;

use App\Entity\Negociant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Negociant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Negociant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Negociant[]    findAll()
 * @method Negociant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NegociantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Negociant::class);
    }


}