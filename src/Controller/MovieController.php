<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;

use App\Entity\DateDiffusion;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function addmovie(Request $request, EntityManagerInterface $em): Response
    {
        $movie = new Movie();

        // Instancier DateDiffusion et l'ajouter au film
        $dateDiffusion = new DateDiffusion();
        $movie->addDateDiffusion($dateDiffusion);

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // boucle pou associez La date et horaire au movie
            foreach ($movie->getDateDiffusions() as $dateDiffusion) {
                $dateDiffusion->setMovie($movie);
                $em->persist($dateDiffusion);
            }
            
            // Persit movie  + dates de diffusion 
            $em->persist($movie);
            $em->flush();
            
            $this->addFlash('success', 'Le film a bien été ajouté avec ses dates de diffusion.');
            return $this->redirectToRoute('home/index.html.twig');
        }
        
        return $this->render('movie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
