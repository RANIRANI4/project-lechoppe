<?php

namespace App\Controller;

use App\Entity\CustomerOrder;
use App\Enum\EnumOrderStatus;
use App\Repository\CustomerOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/orders')]
final class ProducerOrderController extends AbstractController
{
    #[Route('', name: 'app_producer_orders', methods: ['GET'])]
    public function index(CustomerOrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findByProducer($this->getUser());

        return $this->render('producer_order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'app_producer_order_show', methods: ['GET'])]
    public function show(CustomerOrder $order, CustomerOrderRepository $orderRepository): Response
    {
        // Vérifier que la commande contient bien un produit du producteur connecté
        $ownOrders = $orderRepository->findByProducer($this->getUser());
        if (!in_array($order, $ownOrders, true)) {
            throw $this->createAccessDeniedException('Cette commande ne vous appartient pas.');
        }

        return $this->render('producer_order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/update-status', name: 'app_producer_order_update_status', methods: ['POST'])]
    public function updateStatus(
        CustomerOrder           $order,
        Request                 $request,
        CustomerOrderRepository $orderRepository,
        EntityManagerInterface  $entityManager
    ): Response
    {
        $user = $this->getUser();
        $ownOrders = $orderRepository->findByProducer($user);

        if (!in_array($order, $ownOrders, true)) {
            throw $this->createAccessDeniedException('Cette commande ne vous appartient pas.');
        }

        if (!$this->isCsrfTokenValid('update-status' . $order->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $newStatus = $request->getPayload()->getString('status');
        $status = EnumOrderStatus::tryFrom($newStatus);

        if ($status === null) {
            throw $this->createNotFoundException('Statut inconnu.');
        }

        $order->setStatus($status);
        $entityManager->flush();

        return $this->redirectToRoute('app_producer_order_show', ['id' => $order->getId()]);
    }
}
