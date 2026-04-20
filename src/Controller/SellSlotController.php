<?php

namespace App\Controller;

use App\Entity\SellSlot;
use App\Form\SellSlotType;
use App\Repository\SellSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('user/sell-slot')]
final class SellSlotController extends AbstractController
{
    #[Route(name: 'app_sell_slot_index', methods: ['GET'])]
    public function index(SellSlotRepository $sellSlotRepository): Response
    {


        return $this->render('sell_slot/index.html.twig', [
            'sell_slots' => $sellSlotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sell_slot_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_sell_slot_show', methods: ['GET'])]
    public function show(SellSlot $sellSlot): Response
    {
        return $this->render('sell_slot/show.html.twig', [
            'sell_slot' => $sellSlot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sell_slot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SellSlot $sellSlot, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/{id}', name: 'app_sell_slot_delete', methods: ['POST'])]
    public function delete(Request $request, SellSlot $sellSlot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sellSlot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sellSlot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sell_slot_index', [], Response::HTTP_SEE_OTHER);
    }
}
