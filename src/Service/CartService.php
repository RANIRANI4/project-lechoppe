<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const CART_SESSION_KEY = 'cart';

    public function __construct(
        private RequestStack      $requestStack,
        private ProductRepository $productRepository,
    )
    {
    }

    public function add(int $productId): void
    {
        $cart = $this->getCart();
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        $this->saveCart($cart);
    }

    public function remove(int $productId):  void
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return;
        }

        $cart[$productId]--;

        if ($cart[$productId] <= 0) {
            unset($cart[$productId]);
        }

        $this->saveCart($cart);
    }

    public function getQuantity(int $productId): int
    {
        return $this->getCart()[$productId] ?? 0;
    }

    public function count(): int
    {
        return array_sum($this->getCart());
    }

    public function clear(): void
    {
        $this->saveCart([]);
    }

    public function getAll(): array
    {
        $cart = $this->getCart();
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);

            if ($product === null) {
                continue;
            }

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->getPrice() * $quantity,
            ];
        }

        return $items;
    }

    public function getTotal(): float
    {
        $total = 0.0;

        foreach ($this->getAll() as $item) {
            $total += $item['subtotal'];
        }

        return $total;
    }

    private function getCart(): array
    {
        return $this->requestStack->getSession()->get(self::CART_SESSION_KEY, []);
    }

    private function saveCart(array $cart): void
    {
        $this->requestStack->getSession()->set(self::CART_SESSION_KEY, $cart);
    }
}
