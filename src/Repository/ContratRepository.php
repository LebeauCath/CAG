<?php

namespace App\Repository;

use App\Entity\Contrat;
use App\Entity\Silo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


use App\Repository\LivraisonRepository;

/**
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    /**
     * retourne le nombre de contrats pour un négociant donné
     * @param $negociant
     */
    public function nbContrats($negociant)
    {
        $lesContrats = $this->findBy(['negociant' => $negociant]);
        return count($lesContrats);
    }
//Avec DQL --> plus  complexe
//        $query = $this->_em->createQuery('SELECT count(c.nocontrat) FROM App\Entity\Contrat c WHERE c.negociant = ?1');
//        $query->setParameter(1, $negociant);
//        $count = $query->getSingleScalarResult();
//        return $count;
    /**
     * retourne le CA pour un négociant donné
     * @param $negociant
     *
     */
    public function CA($negociant)
    {
        $query = $this->_em->createQuery('SELECT SUM(c.prixcontrat * c.qtecde) FROM App\Entity\Contrat c WHERE c.negociant = ?1');
        $query->setParameter(1, $negociant);
        $ca = $query->getSingleScalarResult();
        if ($ca)
            return $ca;
    }

    /**
     * retourne la dernière date du contrat d'un négociant donné
     * @param $negociant
     */
    public function derniereDate($negociant)
    {
        $query = $this->_em->createQuery('SELECT MAX(c.datecontrat) FROM App\Entity\Contrat c WHERE c.negociant = ?1');
        $query->setParameter(1, $negociant);
        $dd = $query->getSingleScalarResult();
        return $dd;
    }

    /**
     * retourne la liste des contrats avec des informations provenant des livraisons
     */
    public function recupContrats(LivraisonRepository $repoLivraison)
    {
        // Utilisation de findBy sans critères <=> findAll() pour le tri en ordre descendant
        $lesContrats = $this->findBy(
            array(),
            array('nocontrat' => 'DESC')
        );
        if (count($lesContrats) > 0) {
            foreach ($lesContrats as $unContrat) {
                $qte = $repoLivraison->QteLivree($unContrat);
                $unContrat->setQteTotLiv($qte);
            }
        }
        return $lesContrats;
    }

    /**
     * retourne la liste des contrats dans l'état E ou C avec des informations provenant des livraisons
     */
    public function recupContratsEetC($repoLivraison)
    {
        // Utilisation de findBy avec critère sur l'état C ou E et tri en ordre descendant
        $lesContrats = $this->findBy(
            array('etatcontrat' => array('E', 'C')),
            array('nocontrat' => 'DESC')
        );
        if (count($lesContrats) > 0) {
            foreach ($lesContrats as $unContrat) {
                $qte = $repoLivraison->QteLivree($unContrat);
                $unContrat->setQteTotLiv($qte);
            }
        }
        return $lesContrats;
    }


    public function recupereLeContrat($id, LivraisonRepository $repoLivraison)
    {
        $unContrat = $this->find($id);
        if ($unContrat) {
            $qte = $repoLivraison->QteLivree($unContrat);
            $unContrat->setQteTotLiv($qte);
        }
        return $unContrat;
    }


    public function getLesSilos($cereale, SiloRepository $repoSilo)
    {
        $lesSilos = $repoSilo->findBy(
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

