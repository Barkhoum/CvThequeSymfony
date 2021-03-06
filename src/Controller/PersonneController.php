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
    #[Route('/alls', name: 'personne.alls')]
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
            $this->addFlash(type: 'error', message: "La personne n'existe pas !");
            return $this->redirectToRoute('/');
        }
        return $this->render( 'personne/detail.html.twig',['personne'=> $personne]);
    }
    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function addPersonne(personne $personne=null, ManagerRegistry $doctrine, Request $request): Response
    {
        $new = false;

        if(!$personne){
            $new =false;
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class,  $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);

        if($form->isSubmitted()){
           $manager = $doctrine->getManager();
           $manager->persist($personne);

           $manager->flush();
           if($new){
               $message = "a ??t?? ajout?? avec succ??s";
           }else{
                $message = "a ??t?? mis ?? jour avec succ??s";
           }
            $this->addFlash("success", $personne->getName(). $message);
           //Rediriger vers la liste des personnes
            return $this->redirectToRoute('/');
           }else{
                return $this->render(view: 'personne/add-personne.html.twig',
                    parameters: ['form' => $form->createView()
                    ]);
                }
    }
    #[Route('/delete/{id}', name: 'personne.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse{
        //recuperer la personne
        if ($personne){
            $manager = $doctrine->getManager();
            $manager->remove($personne); //Ajoute la fonction de suppression dans la transaction
            $manager->flush();// Ex??cuter la transaction
            $this->addFlash(type:'success', message:'La personne a ??t?? supprim?? avec succ??s');
        //Si la personne existe => alors on va le supprimer et retourner un flashMessage de success

        }else{
            //sinon retourner un flashMessage d'erreur
            $this->addFlash(type:'error', message:'Personne inexistante');
             }
        return $this->redirectToRoute(route: 'personne.list.alls');
        }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'personne.update')]
    public function updatePersonne(Personne $personne = null, ManagerRegistry $doctrine , $name, $firstname, $age){
    //Vefifier que la personne ?? mettre ?? jour existe
        if($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($personne);

            $manager->flush();
            $this->addFlash(type:"success", message:"La personne a ??t?? mise ?? jour avec succ??s");
        }else{

            $this->addFlash(type:"danger", message:"Personne innexistante");
        }
        return $this->redirectToRoute(route: 'personne.list.alls');
    }
}
