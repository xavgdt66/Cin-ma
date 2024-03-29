<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Entity\Salle;
use App\Entity\User;

use App\Entity\DateDiffusion;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Bundle\SecurityBundle\Security;


class MovieController extends AbstractController
{
    #[Route('/movie', name: 'app_movie')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }





    #[Route('/addmovie', name: 'app_movie')]
    public function addmovie(Request $request, EntityManagerInterface $em, Security $security): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }



        $movie = new Movie();

        // Instancier DateDiffusion et l'ajouter au film
        $dateDiffusion = new DateDiffusion();
        $movie->addDateDiffusion($dateDiffusion);

        // Récupérer les salles disponibles pour l'utilisateur courant
        $user = $this->getUser();
        $salles = $user->getSalles();

        $form = $this->createForm(MovieType::class, $movie, ['salles' => $salles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Utiliser la méthode "map" de Doctrine pour associer chaque date de diffusion au film
            $movie->getDateDiffusions()->map(function ($dateDiffusion) use ($movie) {
                $dateDiffusion->setMovie($movie);
            });

            // Persister le film et les dates de diffusion 
            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Le film a bien été ajouté avec ses dates de diffusion.');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('movie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
