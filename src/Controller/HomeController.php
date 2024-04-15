<?php

namespace App\Controller;

use App\Entity\Dishes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/newDish', name:'newDish')]
    public function setName(ManagerRegistry $doctrine ): Response {

        $dish = new Dishes();
        $dish-> setName('Kartoffel Salat');
        $em = $doctrine -> getManager();
        $em -> persist($dish);
        $em -> flush();

        return new Response('query succesfull', 201);
    }

}
