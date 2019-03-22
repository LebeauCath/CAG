<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\NegociantRepository;
use App\Repository\ContratRepository;
use App\Repository\LivraisonRepository;
use App\Entity\Negociant;
use App\Form\NegociantType;

class GererNegociantsController extends AbstractController
{
    /**
     * @Route("/negociants/lister", name="gerer_negociants_lister")
     */
    public function index(NegociantRepository $repo)
    {
        $lesNegociants = $repo->findAll();
        return $this->render('gerer_negociants/index.html.twig', [
            'lesNegociants' => $lesNegociants
        ]);
    }

    /**
     * @Route("/negociants/ajouter", name="gerer_negociants_ajouter")
     */
    public function ajouter(Negociant $leNegociant = null, Request $request, ObjectManager $manager)
    {
        // cette fonction traite deux cas :
        // 1. la demande provenant d'un clic sur un lien "ajouter un négociant"
        // 2. la demande de validation de l'ajout du négociant après avoir rempli le formulaire et cliqué sur le bouton "Ajouter"
        $leNegociant = new Negociant();
        $form = $this->createForm(NegociantType::class, $leNegociant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on est dans le cas du retour du formulaire
            // l'objet Negociant a été automatiquement "hydraté" par Doctrine
            $manager->persist($leNegociant);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Le negociant ' . $leNegociant->getNomnegociant() . ' a été ajouté.'
            );
            return $this->render('accueil/index.html.twig');
        } else {
            // on appelle le formulaire de création
            return $this->render('gerer_negociants/ajouter.html.twig', [
                'formNegociant' => $form->createView()
            ]);
        }

    }

    /**
     * @Route("/negociants/supprimer/{id}", name="gerer_negociants_supprimer")
     */
    public function supprimer(Negociant $leNegociant = null, $id = null, ContratRepository $repoContrat, ObjectManager $manager)
    {
        // Premier cas d'erreur : pas d'id de négociant dans l'URL
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de négociant transmis pour suppression'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        }
        // Deuxième cas d'erreur : l'id du négociant transmis ne correspond à aucun négociant
        if ($leNegociant == null) {
            $this->addFlash(
                'error',
                'Ce négociant n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        }
        // OK
        $nbContrats = $repoContrat->nbContrats($leNegociant);
        if ($nbContrats > 0) {
            $this->addFlash(
                'error',
                'Suppression impossible, ce négociant a des contrats référencés !'
            );
            //return $this->redirectToRoute('gerer_negociants_consulter', ['id' => $leNegociant->getNonegociant()]);
            return $this->redirectToRoute('gerer_negociants_lister');
        } else {
            $manager->remove($leNegociant);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Le négociant ' . $leNegociant->getNomnegociant() . ' a été supprimé !'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        }
    }

    /**
     * @Route("/negociants/consulter/{id}/{option}", name="gerer_negociants_consulter")
     */
    public function consulter($id = null, Negociant $leNegociant = null, ContratRepository $repoContrat, LivraisonRepository $repoLivraison, $option = null)
    {
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de négociant transmis pour consultation'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        }
        // Deuxième cas d'erreur : l'id du négociant transmis ne correspond à aucun négociant
        if ($leNegociant == null) {
            $this->addFlash(
                'error',
                'Ce négociant n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        } else {
            $CA = 0;
            $datedernierContrat = "";
            // récupération de la liste des contrats du négociant
            // Utilisation d'une méthode "magique" du repository findBy+nomAttribut de l'entité ; ici : negociant
            $contratsDuNegociant = $repoContrat->findByNegociant($leNegociant);

            // récupération de la liste des livraisons liées à chaque contrat
            $livraisonsDuNegociant = array();
            if (count($contratsDuNegociant) > 0) {
                foreach ($contratsDuNegociant as $unContrat) {
                    $CA = $CA + $unContrat->getPrixContrat() * $unContrat->getQteCde();
                    // Utilisation d'une méthode "magique" du repository findBy+nomAttribut de l'entité ; ici : contrat
                    $lesLivraisons = $repoLivraison->findByContrat($unContrat);
                    // on fusionne le tableau obtenu au tableau initial
                    $livraisonsDuNegociant = array_merge($livraisonsDuNegociant, $lesLivraisons);
                }
                $datedernierContrat = $repoContrat->derniereDate($leNegociant);
            }
            return $this->render('gerer_negociants/consulter.html.twig', array(
                'leNegociant' => $leNegociant,
                'option' => $option,
                'contratsDuNegociant' => $contratsDuNegociant,
                'livraisonsDuNegociant' => $livraisonsDuNegociant,
                'CA' => $CA,
                'derniereDate' => $datedernierContrat,
            ));
        }
    }

    /**
     * @Route("/negociants/modifier/{id}", name="gerer_negociants_modifier")
     */
    public function modifier($id = null, Negociant $leNegociant = null, Request $request, ObjectManager $manager)
    {

        // cette fonction traite deux cas :
        // 1. la demande provenant d'un clic sur un lien "modifier le négociant"
        // 2. la demande de validation de la modification du négociant après avoir rempli le formulaire et cliqué sur le bouton "Modifier"
        // Dans les deux cas, le négociant à modifier est connu par son id.
        // Grâce au « paramConverter », Symfony fait le lien entre id (de la route) et l’objet $negociant passé en paramètre
        // Premier cas d'erreur : pas d'id de négociant dans l'URL
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de négociant transmis pour modification'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        }
        // Deuxième cas d'erreur : l'id du négociant transmis ne correspond à aucun négociant
        if ($leNegociant == null) {
            $this->addFlash(
                'error',
                'Ce négociant n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_negociants_lister');
        } else {
            $form = $this->createForm(NegociantType::class, $leNegociant);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // on est dans le cas du retour du formulaire
                $manager->persist($leNegociant);
                $manager->flush();
                $this->addFlash(
                    'notice',
                    'Le negociant ' . $leNegociant->getNomnegociant() . ' a été modifié.'
                );
                return $this->redirectToRoute('gerer_negociants_lister');
            } else {
                // on appelle le formulaire de modification
                return $this->render('gerer_negociants/modifier.html.twig', [
                    'formNegociant' => $form->createView()
                ]);
            }
        }
    }
}
