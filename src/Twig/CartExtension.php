<?php

namespace App\Twig;

use App\Service\CartService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function __construct(private CartService $cartService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_quantity', [$this, 'getQuantity']),
            new TwigFunction('cart_count', [$this, 'getCount']),
        ];
    }

    public function getQuantity(int $productId): int
    {
        return $this->cartService->getQuantity($productId);
    }

    public function getCount(): int
    {
        return $this->cartService->count();
    }
}
