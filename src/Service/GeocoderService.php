<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocoderService
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function geocode(string $address): ?array
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
