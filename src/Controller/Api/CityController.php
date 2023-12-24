<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CityController extends AbstractController
{
    #[Route('/city-suggest', name: 'city-suggest', methods:['get'] )]
    public function suggest(Request $request, CityRepository $repository): JsonResponse
    {
        $name = $request->query->getString('name');

        $cities = $repository->searchByName($name);

        return $this->json(['data' => $cities]);
    }
}
