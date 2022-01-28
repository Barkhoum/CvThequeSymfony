<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route("/todo")]
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        //Afficher notre tableau de todo

        //sinon je l'initialise puis l'affiche
        if (!$session->has(name:'todos')){
            $todos =[
                'Achat' => 'Acheter un vélo',
                'Commande' =>  'Allez chercher un ecran pour pc',
                'Cours'=> 'Apprendre Synfony',
                'Correction' => 'Revoir PHP POO'
            ];
            $session ->set('todos', $todos);
            $this->addFlash(type:'info', message:"La liste des todos viens d'être initialisée");
        }
        //si j'ai mon tableau de todo dans ma session je ne fait que l'afficher
        return $this->render(view:'todo/index.html.twig');
    }
    #[Route(
        '/add/{name?test}/{content?test}',// syntaxe courte pour mettre des valeurs par defaut
        name:'todo.add',
        defaults:['content' => 'valeur par défault', 'content => contenu par défaut'] )]//syntaxe perso valeurs par défaut
    public function addTodo(Request $request, $name, $content): RedirectResponse{
        $session = $request->getSession();
        //Verifier si j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
            //si oui
            // Verifier si on a deja un todo avec le meme name
            $todos = $session->get(name:'todos');
            if (isset($todos[$name])){
                //si oui afficher le message d'erreur
                $this->addFlash(type:'danger', message:"le todo avec le $name existe déja dans la liste");
            }else{
                //Si non on l'ajoute et en affiche un message de succés
                $todos[$name] = $content;
                $this->addFlash(type:'success', message:"le todo d'id $name à été ajouté avec success");
                $session->set('todos', $todos);
            }
        }else{
        //si non
        //affiche une erreur et on va rediriger vers le controlleur index
        $this->addFlash(type:'error', message:"La liste des todos n'est pas encore initialiséé");
        }
        return $this->redirectToRoute(route:'todo');
    }
    #[Route('/update/{name}/{content}', name:'todo.update')]
    public function updateTodo(Request $request, $name, $content):RedirectResponse{
        $session = $request->getSession();
        //Verifier si j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
            //si oui
            // Verifier si on a deja un todo avec le meme name
            $todos = $session->get(name:'todos');
            if (!isset($todos[$name])){
                //si oui afficher le message d'erreur
                $this->addFlash(type:'danger', message:"le todo avec le $name n'existe déja dans la liste");
            }else{
                //Si non on l'ajoute et en affiche un message de succés
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash(type:'success', message:"le todo d'id $name à été modifié avec success");

            }
        }else{
            //si non
            //affiche une erreur et on va rediriger vers le controlleur index
            $this->addFlash(type:'error', message:"La liste des todos n'est pas encore initialiséé");
        }
        return $this->redirectToRoute(route:'todo');
    }
    #[Route('/delete/{name}', name:'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse{
        $session = $request->getSession();
        //Verifier si j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
            //si oui
            // Verifier si on a deja un todo avec le meme name
            $todos = $session->get(name:'todos');
            if (!isset($todos[$name])){
                //si oui afficher le message d'erreur
                $this->addFlash(type:'danger', message:"le todo avec le $name existe déja dans la liste");
            }else{
                //Si non on l'ajoute et en affiche un message de succés
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash(type:'success', message:"le todo d'id $name à été supprimé avec success");
            }
        }else{
            //si non
            //affiche une erreur et on va rediriger vers le controlleur index
            $this->addFlash(type:'error', message:"La liste des todos n'est pas encore initialiséé");
        }
        return $this->redirectToRoute(route:'todo');
    }
    #[Route('/reset', name:'todo.reset')]
    public function resetTodo(Request $request):RedirectResponse{
        $session = $request->getSession();
        $session->remove(name:'todos');
        return $this->redirectToRoute(route:'todo');
    }
}