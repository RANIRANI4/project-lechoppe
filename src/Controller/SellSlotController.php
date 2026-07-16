<?php

namespace App\Controller;

use App\Entity\SellSlot;
use App\Enum\EnumState;
use App\Form\AddProductToSellSlotType;
use App\Form\SellSlotType;
use App\Repository\SellSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SellSlotController extends AbstractController
{
    #[Route('/user/sell-slot', name: 'app_sell_slot_index', methods: ['GET'])]
    public function index(SellSlotRepository $sellSlotRepository): Response
    {
        $currentUser = $this->getUser();

        return $this->render('sell_slot/index.html.twig', [
            'sell_slots' => $sellSlotRepository->findActiveByUser($currentUser)->getQuery()->getResult()
        ]);
    }

    #[Route('/user/sell-slot/new', name: 'app_sell_slot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $sellSlot = new SellSlot();
        $form = $this->createForm(SellSlotType::class, $sellSlot, [
            'current_user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sellSlot);
            $entityManager->flush();

            return $this->redirectToRoute('app_sell_slot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sell_slot/new.html.twig', [
            'sell_slot' => $sellSlot,
            'form' => $form,
        ]);
    }

    #[Route('/sell-slot/{id}', name: 'app_sell_slot_show', methods: ['GET'])]
    public function show(SellSlot $sellSlot): Response
    {
        return $this->render('sell_slot/show.html.twig', [
            'sell_slot' => $sellSlot,
        ]);
    }

    #[Route('/user/sell-slot/{id}/edit', name: 'app_sell_slot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SellSlot $sellSlot, EntityManagerInterface $entityManager): Response
    {
        if ($sellSlot->getShop()->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('erreur.');
        }

        $form = $this->createForm(SellSlotType::class, $sellSlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sell_slot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sell_slot/edit.html.twig', [
            'sell_slot' => $sellSlot,
            'form' => $form,
        ]);
    }

    #[Route('/user/sell-slot/{id}', name: 'app_sell_slot_delete', methods: ['POST'])]
    public function delete(Request $request, SellSlot $sellSlot, EntityManagerInterface $entityManager): Response
    {
        if ($sellSlot->getShop()->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('erreur.');
        }

        if ($this->isCsrfTokenValid('delete' . $sellSlot->getId(), $request->getPayload()->getString('_token'))) {
            $sellSlot->setState(EnumState::Inactive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sell_slot_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/sell-slot/{id}/add-product', name: 'app_sell_slot_add_product', methods: ['GET', 'POST'])]
    public function addProduct(SellSlot $sellSlot, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($sellSlot->getShop()->getProducer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('erreur.');
        }

        $currentUser = $this->getUser();

        $form = $this->createForm(AddProductToSellSlotType::class, null, [
            'current_user' => $currentUser,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->get('product')->getData();

            $sellSlot->addProduct($product);
            $entityManager->flush();


            return $this->redirectToRoute('app_sell_slot_show', ['id' => $sellSlot->getId()]);
        }

        return $this->render('sell_slot/add_product.html.twig', [
            'sellSlot' => $sellSlot,
            'form' => $form,
        ]);
    }
}
