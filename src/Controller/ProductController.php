<?php

namespace App\Controller;

use App\Entity\Product;
use App\Enum\EnumState;
use App\Form\AddSellSlotToProductType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProductController extends AbstractController
{
    #[Route('/user/product', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $currentUser = $this->getUser();
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy([
                'state' => EnumState::Active,
                'producer' => $currentUser
            ]),
            'user' => $currentUser,
        ]);
    }

    #[Route('/user/product/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $currentUser = $this->getUser();

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'current_user' => $currentUser
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setProducer($currentUser);

            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    __DIR__ . '/../../public/uploads/products',
                    $newFilename
                );
                $product->setImageFileName($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $product->setSlug($slugger->slug($product->getTitle())->lower() . '-' . $product->getId());

            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/user/product/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('other user');
        }

        $currentUser = $this->getUser();

        $form = $this->createForm(ProductType::class, $product, [
            'current_user' => $currentUser
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    __DIR__ . '/../../public/uploads/products',
                    $newFilename
                );
                $product->setImageFileName($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/user/product/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {

        if ($product->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('other user');
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $product->setState(EnumState::Inactive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/product/{id}/add-sell-slot', name: 'app_product_add_sell_slot', methods: ['GET', 'POST'])]
    public function addSellSlot(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($product->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('other user');
        }

        $currentUser = $this->getUser();

        $form = $this->createForm(AddSellSlotToProductType::class, null, [
            'current_user' => $currentUser,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sellSlot = $form->get('sellSlot')->getData();

            $product->addSellSlot($sellSlot);
            $entityManager->flush();


            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/add_sell_slot.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
}
