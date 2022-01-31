<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render( 'personne/index.html.twig',['personnes'=> $personnes]);
    }
    #[Route('/alls/{page?1}/{nbre?12}', name: 'personne.list.alls')]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]); //24

        $nbrePage = ceil($nbPersonne / $nbre) ;

        $personnes = $repository->findBy([],[],$nbre, offset:($page -1)* $nbre);

        return $this->render( 'personne/index.html.twig',[
            'personnes'=> $personnes,
            'isPaginated' => true,
            'nbrePage'=> $nbrePage,
            'page'=> $page,
            'nbre' => $nbre
        ]);

    }
    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response{
        if (!$personne){
            $this->addFlash(type: 'error', message: "La personne n'existe pas !");
            return $this->redirectToRoute('personne.list');
        }
        return $this->render( 'personne/detail.html.twig',['personne'=> $personne]);
    }
    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstname(firstname:'Guesmia');
        $personne->setName(name:'Barkhoum');
        $personne->setAge(age:'40');


        // ajouter l'operation d'insertion
        $entityManager->persist($personne);


        $entityManager->flush();
        return $this->render('personne/index.html.twig', [
            'personne' => $personne,
        ]);
    }
}
