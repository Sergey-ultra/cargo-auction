<?php

declare(strict_types=1);

namespace App\Services\GeoCoderService;

use App\ValueObject\Point;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoCoderService
{
    public function __construct(private readonly string $apiKey, private readonly HttpClientInterface $client){}

    public function getMapPoint(string $name): ?Point
    {
        $name = urlencode($name);

        $response = $this->client->request(
            'GET',
            "https://geocode-maps.yandex.ru/1.x/?apikey={$this->apiKey}&geocode=$name&format=json",
            [
                'headers' => [
                    'REFERER' => $this->apiKey,
                ]
            ]
        );

        $status = $response->getStatusCode();

        if (200 === $status) {
            $res = json_decode($response->getContent(), true);

            $coordinates = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            $coordinates = explode(' ', $coordinates);
            return new Point((float)$coordinates[1], (float)$coordinates[0]);
        }
        return null;
    }
}
