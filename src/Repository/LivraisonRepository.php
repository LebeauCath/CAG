<?php

namespace App\Repository;


use App\Entity\Livraison;
use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Livraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraison[]    findAll()
 * @method Livraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Livraison::class);
    }


    /**
     * retourne la quantité totale livrée pour un contrat donné
     * @param $contrat
     */
    /* public function QteLivree(Contrat $contrat){
         $query = $this->_em->createQuery('SELECT SUM(l.qteliv) FROM App\Entity\Livraison l WHERE l.contrat = ?1');
         $query->setParameter(1, $contrat);
         $qteLivTotale = $query->getSingleScalarResult();
         if ($qteLivTotale)
             return $qteLivTotale;
         else
             return 0;
     }*/

    public function QteLivree(Contrat $contrat)
    {   $qteLivTotale = 0 ;
        $lesLivraisons = $this->findByContrat($contrat);
        if (count($lesLivraisons) > 0) {
            foreach ($lesLivraisons as $uneLivraison)
            $qteLivTotale += $uneLivraison-> getQteliv();
        }
        return $qteLivTotale;
    }


}