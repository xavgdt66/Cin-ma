<?php

// src/Controller/ReservationController.php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } 

    #[Route('/reservation/{id}', name: 'reservation_film')]
    public function reserve(Request $request, Movie $movie): Response
    {
        $reservation = new Reservation();

        $maxPlaces = $movie->getSalles()->first()->getNombrePlaces();

        $form = $this->createForm(ReservationFormType::class, $reservation, [
            'action' => $this->generateUrl('reservation_film', ['id' => $movie->getId()]),
            'max_places' => $maxPlaces
        ]);

        $form->handleRequest($request);
        dump($form->isSubmitted(), $form->isValid());
        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setMovie($movie);
            $reservation->setNumberOfSeats($form->get('nombrePlaces')->getData());

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('reservation/reserve.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }



}
