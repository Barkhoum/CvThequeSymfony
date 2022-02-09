<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('personne')]

class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list.alls')]
    public function index(ManagerRegistry $doctrine): Response{

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render( 'personne/index.html.twig',['personnes'=> $personnes]);
    }
    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.age')]
    public function statsPersonnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response{

        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render( 'personne/stats.html.twig',[
            'stats'=> $stats[0],
            'ageMin'=>$ageMin,
            'ageMax'=>$ageMax,
        ]);
    }
    #[Route('/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response{

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render( 'personne/index.html.twig',['personnes'=> $personnes]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response{
        if (!$personne){
            $this->addFlash(message: "La personne n'existe pas !");
            return $this->redirectToRoute('/');
        }
        return $this->render( 'personne/detail.html.twig',['personne'=> $personne]);
    }


    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request): Response
    {
        $manager = $doctrine->getManager();
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');// supprime la visibility des champs date

        $form->handleRequest($request);// Mon formulaire va aller traiter la requete
            if($form->isSubmitted()){

                $manager= $doctrine->getManager();
                $manager->persist($personne);
                $manager->flush();

                $this->addFlash($personne->getName(). " a été ajouté avec success!");
                return $this->redirectToRoute('/');
            }else{
                return $this->render(view: 'personne/add-personne.html.twig',
                    parameters: ['form' => $form->createView()]);
                }
         }

    #[Route('/delete/{id}', name: 'personne.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse{
        //recuperer la personne
        if ($personne){
            $manager = $doctrine->getManager();
            $manager->remove($personne); //Ajoute la fonction de suppression dans la transaction
            $manager->flush();// Exécuter la transaction
            $this->addFlash(message: 'La personne a été supprimé avec succès');
        //Si la personne existe => alors on va le supprimer et retourner un flashMessage de success

        }else{
            //sinon retourner un flashMessage d'erreur
            $this->addFlash(message: 'Personne inexistante');
            return $this->redirectToRoute('/');
             }
        return $this->redirectToRoute(route: 'personne.list.alls');
        }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'personne.update')]
    public function updatePersonne(Personne $personne = null, ManagerRegistry $doctrine , $name, $firstname, $age){
    //Vefifier que la personne à mettre à jour existe
        if($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($personne);

            $manager->flush();
            $this->addFlash(message: "La personne a été mise à jour avec succès");
        }else{

            $this->addFlash(message: "Personne innexistante");
        }
        return $this->redirectToRoute(route: 'personne.list.alls');
    }
}
