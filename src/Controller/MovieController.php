<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\DateDiffusion;
use App\Form\MovieType;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class MovieController extends AbstractController
{

    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/addmovie', name: 'app_movie')]
    public function addmovie(Request $request, EntityManagerInterface $em, Security $security): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        $movie = new Movie(); // Instance de Movie 

        $movie->setUser($this->getUser()); // Instance de Movie recupere l'user courant

        $dateDiffusion = new DateDiffusion(); // Instance de DateDiffusion 
        $movie->addDateDiffusion($dateDiffusion); // Movie ajoute uen heure + date sur DateDiffusion

        $user = $this->getUser();
        $salles = $user->getSalles(); // Recup les salles 

        $form = $this->createForm(MovieType::class, $movie, ['salles' => $salles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie->getDateDiffusions()->map(function ($dateDiffusion) use ($movie) {
                $dateDiffusion->setMovie($movie);
            });


            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Le film a bien été ajouté avec ses dates de diffusion.');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('movie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie_show')]
    public function showMovie($id, EntityManagerInterface $em): Response
    {
        $movie = $em->getRepository(Movie::class)->find($id);

        if (!$movie) {
            throw $this->createNotFoundException('Film non trouvé');
        }

        $form = $this->createForm(ReservationFormType::class);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'reservation_form' => $form->createView(),
        ]);
    }



// Editer un film

    #[Route("/movie/editer/{id}", name: "editer_movie")]
    public function editerMovie(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();  

            return $this->redirectToRoute('app_home');
        }

        return $this->render('movie/editer.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

// Supprimer un film

    #[Route("/movie/supprimer/{id}", name: "supprimer_movie")]
    public function supprimerSalle(Request $request, Movie $movie): Response
    {
        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return $this->redirectToRoute('liste_movie'); 
    }





    #[Route("/listmovie", name: "liste_movie")]
    public function listeSalles(): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir la liste des films.');
        }
        $movies = $this->entityManager->getRepository(Movie::class)->findBy(['user' => $user]);
    
        if (!$movies) {
            throw $this->createNotFoundException('Aucun film trouvé pour cet utilisateur.');
        }
    
        return $this->render('movie/liste.html.twig', [
            'movies' => $movies,
        ]);
    }
    


}
