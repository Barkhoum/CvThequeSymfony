<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TabController extends AbstractController
{
    #[Route('/tab{nb<\d+>?5}', name: 'tab')]
    public function index($nb): Response
    {
        $notes = [];
        for($i = 0 ; $i<$nb ; $i++){
            $notes[] = rand(0,20);
        }

        //
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }
    #[Route('/tab/users', name: 'tab.users')]
    public function users(): Response
    {
        $users = [
            ['firstname' =>'Barkhoum', 'name'=>'Guesmia', 'age'=>'40'],
            ['firstname' =>'Tatiana', 'name'=>'Picard', 'age'=>'27'],
            ['firstname' =>'Pascale', 'name'=>'Moreau', 'age'=>'43']
        ];

        return $this->render('tab/users.html.twig', [
            'users' => $users,
        ]);
    }
}
