<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\Modules\City\Infrastructure\Api\CityApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CityController extends AbstractController
{
    #[Route('/city-suggest', name: 'city-suggest', methods:['get'] )]
    public function suggest(Request $request, CityApi $cityApi): JsonResponse
    {
        $name = $request->query->getString('name');

        $cities = $cityApi->searchCitiesByName($name);

        return $this->json(['data' => $cities]);
    }
}
