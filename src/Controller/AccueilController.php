<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="cag_home")
     */
    public function index(SessionInterface $session)
    {
        // on simule un utilisateur connecté (en phase de test)
        $session->set('id',9999);
        $session->set('nom','Dupont');
        $session->set('prenom','Jean');
        return $this->render('accueil/index.html.twig');
    }
}
