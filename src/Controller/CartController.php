<?php

namespace App\Controller;

use App\Entity\Consumer;
use App\Entity\CustomerOrder;
use App\Entity\CustomerOrderItem;
use App\Entity\Product;
use App\Form\ConsumerType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request, CartService $cartService): Response
    {
        if ($this->isCsrfTokenValid('cart-add' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $cartService->add($product->getId());
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(Product $product, Request $request, CartService $cartService): Response
    {
        if ($this->isCsrfTokenValid('cart-remove' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $cartService->remove($product->getId());
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('', name: 'app_cart_show', methods: ['GET'])]
    public function show(CartService $cartService): Response
    {
        return $this->render('cart/show.html.twig', [
            'items' => $cartService->getAll(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request, CartService $cartService): Response
    {
        if ($this->isCsrfTokenValid('cart-clear', $request->getPayload()->getString('_token'))) {
            $cartService->clear();
        }

        return $this->redirectToRoute('app_cart_show');
    }

    #[Route('/checkout', name: 'app_cart_checkout', methods: ['GET', 'POST'])]
    public function checkout(
        Request                $request,
        CartService            $cartService,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($cartService->count() === 0) {
            return $this->redirectToRoute('app_cart_show');
        }

        $consumer = new Consumer();
        $form = $this->createForm(ConsumerType::class, $consumer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order = new CustomerOrder();
            $order->setConsumer($consumer);
            $order->setTotal($cartService->getTotal());

            foreach ($cartService->getAll() as $item) {
                $orderItem = new CustomerOrderItem();
                $orderItem->setProduct($item['product']);
                $orderItem->setQuantity($item['quantity']);
                $orderItem->setUnitPriceAtPurchase($item['product']->getPrice());

                $order->addCustomerOrderItem($orderItem);
            }

            $entityManager->persist($order);
            $entityManager->flush();

            $cartService->clear();

            return $this->redirectToRoute('app_cart_confirmation', [
                'id' => $order->getId()
            ]);
        }

        return $this->render('cart/checkout.html.twig', [
            'form' => $form,
            'total' => $cartService->getTotal(),
            'items' => $cartService->getAll(),
        ]);
    }

    #[Route('/confirmation/{id}', name: 'app_cart_confirmation', methods: ['GET'])]
    public function confirmation(CustomerOrder $order): Response
    {
        return $this->render('cart/confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}
