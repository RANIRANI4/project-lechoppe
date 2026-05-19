<?php

namespace App\Controller;

use App\Repository\ShopRepository;
use App\Service\GeocoderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(
        #[MapQueryParameter(
            filter: \FILTER_VALIDATE_REGEXP,
            flags: \FILTER_NULL_ON_FAILURE,
            options: ['regexp' => '/^[\w\s\-]+$/'])]
        ?string         $location,
        Request         $request,
        ShopRepository  $shopRepository,
        GeocoderService $geocoder
    ): Response
    {
        $shops = $shopRepository->findAll();

        if (!empty($location)) {
            $coords = $geocoder->geocode($location);

            if ($coords) {
                $shops = $shopRepository->findByDistance(
                    $coords['latitude'],
                    $coords['longitude']
                );
            }
        }

        return $this->render('home.html.twig', [
            'shops' => $shops,
            'location' => $location,
        ]);
    }

}
