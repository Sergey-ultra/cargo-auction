<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\CityFilterDTO;
use App\Modules\City\Infrastructure\Api\CityApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CityController extends AbstractController
{
    #[Route('/city/suggest', name: 'city-suggest', methods:['get'] )]
    public function suggest(#[MapQueryString] ?CityFilterDTO $filter, CityApi $cityApi): JsonResponse
    {
        $cities = $cityApi->searchCitiesByName($filter->name);

        return $this->json(['data' => $cities]);
    }

    #[Route('/city/by-id/{id}', name: 'city-by-id', requirements: ['id' => '\d+'], methods: ['get'])]
    public function byId(int $id, CityApi $cityApi): JsonResponse
    {
        $city = $cityApi->getCityById($id);

        return $this->json(['data' => $city]);
    }
}
