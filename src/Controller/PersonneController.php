<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{
    #[Route('/personne/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstname(firstname:'Guesmia');
        $personne->setName(name:'Barkhoum');
        $personne->setAge(age:'40');
        //$personne2 = new Personne();
        //$personne2->setFirstname(firstname:'Picard');
        //$personne2->setName(name:'Tatiana');
        //$personne2->setAge(age:'27');

        // ajouter l'operation d'insertion
        $entityManager->persist($personne);
        $entityManager->persist($personne2);

        $entityManager->flush();
        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }
}
