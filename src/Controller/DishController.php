<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishType;
use App\Repository\DishesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dishes', name: 'dishes.')] //route prefix
class DishController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(DishesRepository $dish)
    {
        $dishes = $dish->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes
        ]);
    }

    #[Route('/create', name: 'newDish')]
    public function newdDish(ManagerRegistry $doctrine, Request $request): Response
    {

        $dish = new Dishes();

        //Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Entity Manager
            $em = $doctrine->getManager();

            // $image = $request->files->get('attachment');
            $image = $form->get('attachment')->getData();      
            if ($image) {
                $filename = md5(uniqid()) .'.'. $image->guessExtension(); //creo un id unico al archivo subido por el cliente

            }

            $image->move($this->getParameter('images_folder'), $filename);

            $dish->setImage($filename);
            $em->persist($dish);
            $em->flush();
            $this->addFlash('success', 'Dish created successfully');
            return $this->redirect($this->generateUrl('dishes.index'));
        }

        return $this->render('dish/create.html.twig', [
            'createDishForm' => $form->createView()
        ]);
    }
    #[Route('/delete/{id}', name: 'deleteDish')]
    public function deleteDIsh(ManagerRegistry $doctrine, DishesRepository $dr, int $id)
    {

        $em = $doctrine->getManager();
        $dishes = $dr->find($id); //find trae el objeto por id & findAll te trae un array con los objetos de la identidad
        $em->remove($dishes);
        $em->flush();

        $this->addFlash('success', 'Dish removed successfully');
        return $this->redirect($this->generateUrl('dishes.index'));
    }
    #[Route('/show/{id}', name: 'showImgDish')]
    public function show(Dishes $dish, int $id){ //@ParamConverter
        return $this->render('dish/show.html.twig', [
            'dish' => $dish
        ]);
    }
}
