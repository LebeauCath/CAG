<?php
/**
 * Contrôleur secondaire chargé de la gestion des contrats
 * @author  dk -
 * @package default (mission 6 - symfony)
 */

namespace App\Controller;

use App\Entity\Livraison;
use App\Repository\SiloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ContratRepository;
use App\Repository\LivraisonRepository;
use App\Form\LivraisonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class gererLivraisonsController extends AbstractController
{
    //affiche les contrats dans l'état 'C' et 'E'

    /**
     * @Route("/livraisons/lister", name="gerer_livraisons_lister")
     */
    public function index(ContratRepository $repo, LivraisonRepository $repoLivraison)
    {
        $lesContrats = $repo->recupContratsEetC($repoLivraison);
        return $this->render('gerer_livraisons/index.html.twig', array(
            'lesContrats' => $lesContrats,
        ));
    }


    /**
     * @Route("/livraisons/consulter/{id}", name="gerer_livraisons_consulter")
     */
    public function consulter($id = null, ContratRepository $repo, LivraisonRepository $repoLivraison)
    {
        if ($id == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de contrat transmis pour consultation'
            );
            return $this->redirectToRoute('gerer_livraisons_lister');
        }

        // récuprération du contrat et de ses informations de livraison
        $leContrat = $repo->recupereLeContrat($id, $repoLivraison);
        // Deuxième cas d'erreur : l'id du contrat transmis ne correspond à aucun contrat
        if ($leContrat == null) {
            $this->addFlash(
                'error',
                'Ce contrat n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_livraisons_lister');
        } else {
            if ($leContrat->getEtatcontrat() != 'E' && $leContrat->getEtatcontrat() != 'C') {
                $this->addFlash(
                    'notice',
                    'Les livraisons de ce contrat ne sont pas pas modifiables car il est annulé ou soldé'
                );
            }
            $lesLivraisons = NULL;
            if ($leContrat->getQteTotLiv() != 0) {
                $lesLivraisons = $repoLivraison->findByContrat($leContrat);
            }
            return $this->render('gerer_livraisons/consulter.html.twig', array(
                    'leContrat' => $leContrat,
                    'lesLivraisons' => $lesLivraisons)
            );
        }
    }

    /**
     * @Route("/livraisons/ajouter/{idContrat}", name="gerer_livraisons_ajouter")
     */
    public function ajouter($idContrat = null, Livraison $laLivraison = null, ContratRepository $repo, LivraisonRepository $repoLivraison, SiloRepository $repoSilo, Request $request, ObjectManager $manager)
    {
        if ($idContrat == null) {
            $this->addFlash(
                'error',
                'Pas d\'id de contrat transmis pour nouvelle livraison'
            );
            return $this->redirectToRoute('gerer_livraisons_lister');
        }

        // récupération du contrat et de ses informations de livraison
        $leContrat = $repo->recupereLeContrat($idContrat, $repoLivraison);
        if ($leContrat == null) {
            $this->addFlash(
                'error',
                'Ce contrat n\'existe pas !'
            );
            return $this->redirectToRoute('gerer_livraisons_lister');
        }

        $qteMaxAlivrer = $leContrat->getQtecde() - $leContrat->getQteTotLiv();
        // pas de problème sur le contrat (on peut ajouter des vérifications sur l'état)
        if (!$laLivraison) {

            $cereale = $leContrat->getCereale();
            // ai passé beaucoup de temps à résoudre un pb que je n'avais pas au début
            // lié à la double mention
            // $lesSilos = $repoSilo->getLesSilos($cereale);
            //dump($lesSilos);
            $lesSilos = $repo->getLesSilos($cereale, $repoSilo);
            dump($lesSilos);
            $laLivraison = new Livraison();
            $laLivraison->setContrat($leContrat);
            $laLivraison->setDateliv(new \DateTime());
            $laLivraison->setQteliv($qteMaxAlivrer);
        }

        // passage du paramètre lesSilos dans le constructeur du formulaire via une tableau
        //$form = $this->createForm(LivraisonType::class, $laLivraison, ['lesSilos' => $lesSilos]);

        // solution plus simple sans utiliser LivraisonType et le passage des paramètres
        $form = $this->createFormBuilder($laLivraison)
            ->add('dateliv', DateType::class, array(
                'label' => 'Date',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('qteliv', TextType::class, array(
                'label' => 'Quantité',
                'attr' => array('size' => 10)
            ))
            ->add('silo', EntityType::class, array(
                'class' => 'App\Entity\Silo',
                'choice_label' => 'codesilo',
                'choices' => $lesSilos
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hasError = false;
            if ($laLivraison->getDateliv() == NULL) {
                $this->addFlash(
                    'error',
                    "La date doit être renseignée !"
                );
                $hasError = true;
            }
            if (empty($laLivraison->getQteliv())) {
                $this->addFlash(
                    'error',
                    "La quantité doit être renseignée !"
                );
                $hasError = true;
            }
            if ($laLivraison->getQteliv() > $qteMaxAlivrer) {
                $this->addFlash(
                    'error',
                    "La quantité doit être inférieure à la quantité restant à livrer !"
                );
                $hasError = true;
            }
            if (empty($laLivraison->getSilo())) {
                $this->addFlash(
                    'error',
                    "Le silo doit être renseigné !"
                );
                $hasError = true;
            }
            if (!$hasError) {
                // modification éventuelle du statut du contrat E --> C ou C --> S
                if ($leContrat->getQteTotLiv() == 0) {
                    $leContrat->setEtatcontrat('C');
                } else if ($laLivraison->getQteliv() == $qteMaxAlivrer) {
                    $leContrat->setEtatcontrat('S');
                }

                $manager->persist($laLivraison);
                $manager->flush();

                // affichage du message dans la vue récapitulative
                $message = 'La livraison ' . $laLivraison->getNolivraison() . ' a été ajoutée.';
                if ($leContrat->getEtatcontrat() == 'S') {
                    $message .= " Contrat soldé.";
                } else {
                    $message .= " Contrat en cours.";
                }
                $this->addFlash(
                    'notice',
                    $message
                );
                return $this->render('gerer_livraisons/recap_ajout.html.twig', array(
                        'laLivraison' => $laLivraison)
                );
            } else {
                return $this->redirectToRoute('gerer_livraisons_lister');
            }
        } else {
            // on appelle le formulaire de création
            return $this->render('gerer_livraisons/ajouter.html.twig', [
                'formLivraison' => $form->createView(),
                'leContrat' => $leContrat,
                'lesSilos' => $lesSilos
            ]);
        }
    }
}

