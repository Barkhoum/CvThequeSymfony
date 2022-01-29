<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FirstController extends AbstractController
{

    #[Route('/template', name: 'template')]
    public function template(){
        return $this->render(view: 'template.html.twig');
    }

    #[Route('/order/{maVar}', name: 'test.order.route')]
    public function testOrderRoute($maVar){
        return new Response("<html><body>$maVar</body><html>");
    }
    #[Route('/first', name: 'first')]//route c'est antislash puis le nom que l'on souhaite afficher puis le name correspont à l'ID de la class!
    public function index(): Response
    {
        //chercher au niveau de la base de données vos users
        return $this->render('first/index.html.twig',[
        'name' => 'Dupont',
        'firstname' => 'Marie',
            'path' => ''

        ]);
    }

//#[Route('/sayHello/{name}/{firstname}', name:'say.hello')]

    public function sayHello(Request $request, $name, $firstname): Response
    {

       return $this->render(view: 'first/hello.html.twig', parameters: [
           'nom' => $name,
           'prenom' => $firstname
        ]);

    }
    #[Route(
        'multi/{entier1}/{entier2}',// version courte requirement ajouter le regex {entier1<\d+>}
        name: 'multiplication',
        requirements: ['entier1'=> '\d+', 'entier2' => '\d+']
    )]
    public function multiplication($entier1, $entier2){
        $resultat = $entier1 * $entier2;
        return new Response( content: "<h1>Le resultat de la multiplication est de $resultat</h1>");
    }
}
