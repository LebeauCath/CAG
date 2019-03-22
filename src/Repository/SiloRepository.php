<?php

namespace App\Repository;

use App\Entity\Silo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Silo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Silo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Silo[]    findAll()
 * @method Silo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Silo::class);
    }

    public function getLesSilos($cereale) {
        $lesSilos = $this->findBy(
            array('cereale' => $cereale)
        );
       // $lesSilos = $this->findByCereale($cereale);
        /*$query = $this->_em->createQuery('SELECT  FROM App\Entity\Silo s WHERE c.negociant = ?1');
        $query->setParameter(1, $negociant);
        $dd = $query->getSingleScalarResult();
        return $dd;*/

        //$lesSilos = $this->findAll();
        dump($cereale);
        return $lesSilos;
    }
}