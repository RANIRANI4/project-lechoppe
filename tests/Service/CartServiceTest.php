<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use PHPUnit\Framework\MockObject\Stub\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends TestCase
{
    private CartService $cartService;
    private ProductRepository $productRepository;
    protected function setUp(): void
    {
        $session = new Session(new MockArraySessionStorage());

        $requestStack = $this->createStub(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $this->productRepository = $this->createStub(ProductRepository::class);

        $this->cartService = new CartService($requestStack, $this->productRepository);
    }

    public function testAddIncrementsQuantity(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(1);
        $this->cartService->add(2);

        $this->assertSame(2, $this->cartService->getQuantity(1));
        $this->assertSame(1, $this->cartService->getQuantity(2));
        $this->assertSame(3, $this->cartService->count());
    }

    public function testRemoveDeletesProductWhenQuantityReachesZero(): void
    {
        $this->cartService->add(1);
        $this->cartService->remove(1);

        $this->assertSame(0, $this->cartService->getQuantity(1));
        $this->assertSame(0, $this->cartService->count());
    }

    public function testRemoveUnknownProductDoesNothing(): void
    {
        $this->cartService->remove(99);

        $this->assertSame(0, $this->cartService->count());
    }

    public function testGetTotalSumsSubtotals(): void
    {
        $productA = (new Product())->setPrice(250);
        $productB = (new Product())->setPrice(100);

        $this->productRepository
            ->method('find')
            ->willReturnMap([
                [1, null, null, $productA],
                [2, null, null, $productB],
            ]);

        $this->cartService->add(1);
        $this->cartService->add(1);
        $this->cartService->add(2);

        // (250 * 2) + (100 * 1)
        $this->assertSame(600.0, $this->cartService->getTotal());
    }

    public function testGetAllIgnoresDeletedProduct(): void
    {
        $this->productRepository
            ->method('find')
            ->willReturn(null);

        $this->cartService->add(42);

        $this->assertSame([], $this->cartService->getAll());
        $this->assertSame(0.0, $this->cartService->getTotal());
    }
}
