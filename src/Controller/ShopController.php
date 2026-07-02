<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Form\ShopFormType;
use App\Repository\ShopRepository;
use App\Service\GeocoderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShopController extends AbstractController
{


    #[Route('/shop',name: 'app_shop_index', methods: ['GET'])]
    public function index(ShopRepository $shopRepository): Response
    {
        return $this->render('shop/index.html.twig', [
            'shops' => $shopRepository->findAll(),
        ]);
    }

    #[Route('/user/shop/new', name: 'app_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GeocoderService $geocoder): Response
    {
        $shop = new Shop();
        $form = $this->createForm(ShopFormType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $shop->setProducer($this->getUser());

            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    __DIR__ . '/../../public/uploads/shops',
                    $newFilename
                );
                $shop->setImageFileName($newFilename);
            }
            $fullAddress = $shop->getAddress() . ' ' . $shop->getZipCode() . ' ' . $shop->getCity();
            $coords = $geocoder->geocode($fullAddress);
            if ($coords) {
                $shop->setLatitude($coords['latitude']);
                $shop->setLongitude($coords['longitude']);
            }

            $entityManager->persist($shop);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_show', ['id' => $shop->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop/new.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/shop/{id}', name: 'app_shop_show', methods: ['GET'])]
    public function show(Shop $shop): Response
    {
        return $this->render('shop/show.html.twig', [
            'shop' => $shop,
        ]);
    }

    #[Route('/user/shop/{id}/edit', name: 'app_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shop $shop, EntityManagerInterface $entityManager, GeocoderService $geocoder): Response
    {
        $form = $this->createForm(ShopFormType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    __DIR__ . '/../../public/uploads/shops',
                    $newFilename
                );
                $shop->setImageFileName($newFilename);
            }
            $fullAddress = $shop->getAddress() . ' ' . $shop->getZipCode() . ' ' . $shop->getCity();
            $coords = $geocoder->geocode($fullAddress);
            if ($coords) {
                $shop->setLatitude($coords['latitude']);
                $shop->setLongitude($coords['longitude']);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_show', ['id' => $shop->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop/edit.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/user/shop/{id}', name: 'app_shop_delete', methods: ['POST'])]
    public function delete(Request $request, Shop $shop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shop->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_account', [], Response::HTTP_SEE_OTHER);
    }
}
