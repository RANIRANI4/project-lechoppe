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
    private const SHOPS_PER_PAGE = 12;

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(
        #[MapQueryParameter(
            filter: \FILTER_VALIDATE_REGEXP,
            flags: \FILTER_NULL_ON_FAILURE,
            options: ['regexp' => '/^[\w\s\-]+$/'])]
        ?string         $location,
        #[MapQueryParameter(
            filter: \FILTER_VALIDATE_INT,
            flags: \FILTER_NULL_ON_FAILURE,
            options: ['options' => ['min_range' => 1]])]
        ?int            $page,
        Request         $request,
        ShopRepository  $shopRepository,
        GeocoderService $geocoder
    ): Response
    {
        $page = $page ?? 1;
        $totalPages = null;

        if (!empty($location)) {
            $coords = $geocoder->geocode($location);

            if ($coords) {
                $shops = $shopRepository->findByDistance(
                    $coords['latitude'],
                    $coords['longitude']
                );
            } else {
                $shops = [];
            }
        } else {
            $result = $shopRepository->findPaginated($page, self::SHOPS_PER_PAGE);
            $shops = $result['shops'];
            $totalPages = (int) ceil($result['total'] / self::SHOPS_PER_PAGE);
        }

        return $this->render('home.html.twig', [
            'shops' => $shops,
            'location' => $location,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
