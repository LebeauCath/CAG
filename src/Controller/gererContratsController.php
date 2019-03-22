<?php
/**
 * Contrôleur secondaire chargé de la gestion des contrats
 * @author  dk -
 * @package default (mission 6 - symfony)
 */

namespace App\Controller;


use App\Form\ContratType;
use App\Form\ContratEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Contrat;
use App\Repository\ContratRepository;
use App\Repository\LivraisonRepository;


class gererContratsController extends AbstractController
{
    /**
     * @Route("/contrats/lister", name="gerer_contrats_lister")
     */
    public function index(ContratRepository $repo, LivraisonRepository $repoLivraison)
    {

        // Utilisation de findBy sans critères <=> findAll() pour le tri en ordre descendant
        $lesContrats = $repo->recupContrats($repoLivraison);
        return $this->render('gerer_contrats/index.html.twig', array(
            'lesContrats' => $lesContrats,
        ));
    }

    /**
     * @Route("/contrats/consulter/{id}", name="gerer_contrats_consulter")
     */
    public function consulter($id = null, ContratRepository $repo, LivraisonRepository $repoLivraison)
    {
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de contrat transmis pour consultation'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        }

        // récuprération du contrat et de ses informations de livraison
        $leContrat = $repo->recupereLeContrat($id, $repoLivraison);
        // Deuxième cas d'erreur : l'id du négociant transmis ne correspond à aucun négociant
        if ($leContrat == null) {
            $this->addFlash(
                'error',
                'Ce contrat n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        } else {
            if ($leContrat->getEtatcontrat() != 'E') {
                $this->addFlash(
                    'notice',
                    'Ce contrat n\'est pas modifiable et ne peut être supprimé car il est en cours ou soldé !'
                );
            }
            $lesLivraisons = NULL;
            if ($leContrat->getQteTotLiv() != 0) {
                $lesLivraisons = $repoLivraison->findByContrat($leContrat);
            }
            return $this->render('gerer_contrats/consulter.html.twig', array(
                    'leContrat' => $leContrat,
                    'lesLivraisons' => $lesLivraisons)
            );
        }
    }

    /**
     * @Route("/contrats/ajouter", name="gerer_contrats_ajouter")
     */
    public function ajouter(Contrat $leContrat = null, Request $request, ObjectManager $manager)
    {
        if (!$leContrat) {
            $leContrat = new Contrat();
            $leContrat->setDatecontrat(new \DateTime());
            $leContrat->setEtatcontrat('E');// règles de gestion : en cours
        }
        $form = $this->createForm(ContratType::class, $leContrat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hasError = false;
            if ($leContrat->getDatecontrat() == NULL) {
                $this->addFlash(
                    'error',
                    "La date doit être renseignée !"
                );
                $hasError = true;
            }
            if (empty($leContrat->getQtecde())) {
                $this->addFlash(
                    'error',
                    "La quantité doit être renseignée !"
                );
                $hasError = true;
            }
            if (empty($leContrat->getPrixcontrat())) {
                $this->addFlash(
                    'error',
                    "Le prix doit être renseigné !"
                );
                $hasError = true;
            }
            if (!$hasError) {
                $manager->persist($leContrat);
                $manager->flush();
                $this->addFlash(
                    'notice',
                    'Le contrat ' . $leContrat->getNocontrat() . ' a été ajouté.'
                );
                return $this->render('gerer_contrats/recap_ajout.html.twig', array(
                        'leContrat' => $leContrat)
                );
            }
        }
        // on appelle le formulaire de création
        return $this->render('gerer_contrats/ajouter.html.twig', [
            'formContrat' => $form->createView()
        ]);
    }

    /**
     * @Route("/contrats/modifier/{id}", name="gerer_contrats_modifier")
     */
    public function modifier($id = null, Contrat $leContrat = null, Request $request, ObjectManager $manager)
    {
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de contrat transmis pour modification'
            );

        }
        // Deuxième cas d'erreur : l'id du contrat transmis ne correspond à aucun contrat
        if (!$leContrat) {
            $this->addFlash(
                'error',
                'Ce contrat n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        }

        if ($leContrat->getEtatcontrat() == 'C' || $leContrat->getEtatcontrat() == 'S') {
            $this->addFlash(
                'error',
                "Le contrat ne peut être modifié : état en cours ou soldé !"
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        }
        // pas d'erreur, apppel du formulaire de modification avec la variable $contrat
        $form = $this->createForm(ContratEditType::class, $leContrat);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hasError = false;
            if ($leContrat->getDatecontrat() == NULL) {
                $this->addFlash(
                    'error',
                    "La date doit être renseignée !"
                );
                $hasError = true;
            }
            if (empty($leContrat->getQtecde())) {
                $this->addFlash(
                    'error',
                    "La quantité doit être renseignée !"
                );
                $hasError = true;
            }
            if (empty($leContrat->getPrixcontrat())) {
                $this->addFlash(
                    'error',
                    "Le prix doit être renseigné !"
                );
                $hasError = true;
            }
            if (!$hasError) {
                $manager->flush();
                $this->addFlash(
                    'notice',
                    'Le contrat ' . $leContrat->getNocontrat() . ' a été modifié.'
                );
                return $this->render('gerer_contrats/consulter.html.twig', array(
                        'leContrat' => $leContrat)
                );
            }
        } else {
            // on appelle le formulaire de création
            return $this->render('gerer_contrats/modifier.html.twig', [
                'formContrat' => $form->createView(),
                'leContrat' => $leContrat
            ]);
        }
    }

    /**
     * @Route("/contrats/supprimer/{id}", name="gerer_contrats_supprimer")
     */
    public function supprimer($id = null, Contrat $leContrat = null, Request $request, ObjectManager $manager)
    {
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de contrat transmis pour suppression'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        }
        // Deuxième cas d'erreur : l'id du contrat transmis ne correspond à aucun contrat
        if (!$leContrat) {
            $this->addFlash(
                'error',
                'Ce contrat n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        }
        // peut-on supprimer le contrat ?
        if ($leContrat->getEtatcontrat() == 'E') {
            $manager->remove($leContrat);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Le contrat ' . $leContrat->getNocontrat() . ' a été supprimé !'
            );
            return $this->redirectToRoute('gerer_contrats_lister');
        } else {
            $this->addFlash(
                'error',
                'Le contrat ne peut pas être supprimé, il est en cours ou soldé'
            );
            return $this->render('gerer_contrats/consulter.html.twig', array(
                    'leContrat' => $leContrat)
            );
        }
    }


}

