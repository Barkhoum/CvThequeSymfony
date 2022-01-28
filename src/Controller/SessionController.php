<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if($session->has(name:'nbVisite')){
            $nbreVisite = $session->get(name: 'nbVisite') + 1;

        }else{
            $nbreVisite = 1;
        }
        $session->set('nbVisite', $nbreVisite);
        return $this->render('session/index.html.twig');
    }
}
