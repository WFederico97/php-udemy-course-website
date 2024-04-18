<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_orders')]
    public function index(OrdersRepository $or): Response
    {
        $orders = $or->findBy(
            ['counter' => 'Counter 1']
        );
        // dump($orders);
        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }
    #[Route('/order/{id}', name: 'app_order')]
    public function order(ManagerRegistry $doctrine ,Dishes $dish){
        $orders = new Orders();
        $orders->setCounter('Counter 1');
        $orders->setName($dish->getName());
        $orders->setOrderNumber($dish->getId());
        $orders->setPrice($dish->getPrice());
        $orders->setStatus('open');

        $em = $doctrine->getManager();
        $em -> persist($orders);
        $em -> flush();

        $this->addFlash('orders', $orders->getName(). ' was added to the order');

        return $this->redirect($this->generateUrl('app_menu'));
    }
    #[Route('/status/{id}, {status}', name: 'app_status')]
    public function status(OrdersRepository $or,ManagerRegistry $doctrine ,$id,$status){
        $em = $doctrine->getManager();
        $order = $or->find($id) ;
        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('app_orders'));
    }
    #[Route('/delete/{id}', name: 'app_order_delete')]
    public function deleteOrder(ManagerRegistry $doctrine, OrdersRepository $or, int $id)
    {

        $em = $doctrine->getManager();
        $order = $or->find($id); //find trae el objeto por id & findAll te trae un array con los objetos de la identidad
        $em->remove($order);
        $em->flush();

        $this->addFlash('success', 'Order removed successfully');
        return $this->redirect($this->generateUrl('app_orders'));
    }
}
