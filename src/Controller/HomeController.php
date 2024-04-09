<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function usersWithRoleCinema(EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class); // Recup user 
        $usersWithRoleCinema = $userRepository->findByRole('ROLE_CINEMA'); // Recup user cinema 
    
        return $this->render('home/index.html.twig', [
            'users' => $usersWithRoleCinema,
        ]);
    }
    
    
  

}
