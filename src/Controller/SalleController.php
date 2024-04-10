<?php
// src/Controller/SalleController.php
namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;



use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class SalleController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security; 
    }

    #[Route("/salle/ajouter", name: "ajouter_salle")]
    public function ajouterSalle(Request $request, Security $security): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {  
            return $this->redirectToRoute('app_home');
        }

        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            if (!$user) {
                throw $this->createAccessDeniedException('Vous devez être connecté pour ajouter une salle.');
            }
            $salle->setUser($user);

            $this->entityManager->persist($salle);
            $this->entityManager->flush();

            return $this->redirectToRoute('liste_salles');
        }

        return $this->render('salle/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/salle/editer/{id}", name: "editer_salle")]
    public function editerSalle(Request $request, Salle $salle, Security $security): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {  
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('liste_salles');
        }

        return $this->render('salle/editer.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/salle/supprimer/{id}", name: "supprimer_salle")]
    public function supprimerSalle(Request $request, Salle $salle, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {  
            return $this->redirectToRoute('app_home');
        }
        $this->entityManager->remove($salle);
        $this->entityManager->flush();

        return $this->redirectToRoute('liste_salles');
    }

    #[Route("/salles", name: "liste_salles")]
    public function listeSalles(Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if (!$security->isGranted('ROLE_CINEMA') && !$security->isGranted('ROLE_ADMIN')) {  
            return $this->redirectToRoute('app_home');
        }
        
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir la liste des salles.');
        }
        $salles = $this->entityManager->getRepository(Salle::class)->findBy(['user' => $user]);

        return $this->render('salle/liste.html.twig', [
            'salles' => $salles,
        ]);
    }
}
