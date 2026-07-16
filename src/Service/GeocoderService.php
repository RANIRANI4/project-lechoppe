<?php

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocoderService
{
    private const CACHE_TTL = 2592000; // 30 jours

    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface      $cache
    )
    {
    }

    public function geocode(string $address): ?array
    {
        $key = 'geocode_' . md5($address);

        return $this->cache->get($key, function (ItemInterface $item) use ($address) {
            $item->expiresAfter(self::CACHE_TTL);

            return $this->fetchCoordinates($address);
        });
    }

    private function fetchCoordinates(string $address): ?array
    {
        $response = $this->httpClient->request('GET', 'https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
            ],
            'headers' => [
                'User-Agent' => 'Echoppe/1.0'
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $data = $response->toArray();

        if (empty($data)) {
            return null;
        }

        return [
            'latitude' => (float)$data[0]['lat'],
            'longitude' => (float)$data[0]['lon'],
        ];
    }
}
