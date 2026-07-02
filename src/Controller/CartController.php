<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
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
}
