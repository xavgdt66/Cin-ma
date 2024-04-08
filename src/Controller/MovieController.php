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



use Symfony\Component\String\Slugger\SluggerInterface; 
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile; 




class MovieController extends AbstractController
{

    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
/*
    #[Route('/addmovie', name: 'app_movie')]
    public function addmovie(Request $request, EntityManagerInterface $em, Security $security, SluggerInterface $slugger): Response
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
    
            $brochureFile = $form->get('brochure')->getData();
    
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename); 
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où les brochures sont stockées
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
                }
    
                // Met à jour la propriété 'brochureFilename' pour stocker le nom du fichier PDF
                // au lieu de son contenu
                $movie->setBrochureFilename($newFilename);
            }

            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Le film a bien été ajouté avec ses dates de diffusion.');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('movie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }*/

    #[Route('/addmovie', name: 'app_movie')]
public function addmovie(Request $request, EntityManagerInterface $em, Security $security, SluggerInterface $slugger): Response
{

    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

    if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('app_home');
    }

    $movie = new Movie(); // Instance de Movie 

    $movie->setUser($this->getUser()); // Instance de Movie récupère l'utilisateur courant

    $dateDiffusion = new DateDiffusion(); // Instance de DateDiffusion 
    $movie->addDateDiffusion($dateDiffusion); // Movie ajoute une heure + date sur DateDiffusion

    $user = $this->getUser();
    $salles = $user->getSalles(); // Récupère les salles 

    $form = $this->createForm(MovieType::class, $movie, ['salles' => $salles]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $movie->getDateDiffusions()->map(function ($dateDiffusion) use ($movie) {
            $dateDiffusion->setMovie($movie);
        });

        $brochureFile = $form->get('brochure')->getData();

        // Cette condition est nécessaire car le champ 'brochure' n'est pas requis
        // Donc le fichier PDF doit être traité uniquement lorsqu'un fichier est téléchargé
        if ($brochureFile) {
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // Ceci est nécessaire pour inclure en toute sécurité le nom de fichier en tant que partie de l'URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

            // Déplacer le fichier vers le répertoire où les brochures sont stockées
            try {
                $brochureFile->move(
                    $this->getParameter('brochures_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            // Met à jour la propriété 'brochureFilename' pour stocker le nom du fichier PDF
            // au lieu de son contenu
            $movie->setBrochureFilename($newFilename);
        }

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
