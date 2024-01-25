<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\LoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ListController extends AbstractController
{
    #[Route('/list', name: 'api.list', methods:['get'])]
    public function index(): JsonResponse
    {
        $perPageOptions = [10, 20, 30, 50, 100];

        return $this->json(['data' => [
                'perPageOptions' => $perPageOptions,
                'orderOptions' => LoadRepository::ORDER_OPTIONS,
            ]]
        );
    }
}
