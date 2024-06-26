<?php

namespace App\Controller;

use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DishesRepository $dr): Response
    {
        $dishes = $dr->findAll();
        $rnd = array_rand($dishes,2);

        return $this->render('home/index.html.twig', [
            'dish1' => $dishes[$rnd[0]],
            'dish2' => $dishes[$rnd[1]],
        ]);
    }

}
