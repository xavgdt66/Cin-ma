<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function usersWithRoleCinema(EntityManagerInterface $entityManager): Response
    {
          // Recup repository
          $userRepository = $entityManager->getRepository(User::class);
    
          // recup les users pour role cinema
          $usersWithRoleCinema = $userRepository->findByRole('ROLE_CINEMA');
      
          $emails = array_map(function($user) {
              return $user->getEmail();
          }, $usersWithRoleCinema);
      
          return $this->render('home/index.html.twig', [
              'emails' => $emails,
          ]);
    }

    
  

}
