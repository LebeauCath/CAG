<?php

namespace App\Repository;

use App\Entity\Cereale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cereale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cereale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cereale[]    findAll()
 * @method Cereale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CerealeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cereale::class);
    }
}