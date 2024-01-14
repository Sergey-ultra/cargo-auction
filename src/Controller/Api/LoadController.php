<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\LoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadController extends AbstractController
{
    #[Route('/sendBid/{id}', name: 'cargo.showBidForm', methods:['post'])]
    public function showBidForm(int $id, LoadRepository $loadRepository): JsonResponse
    {
        $load = $loadRepository->find($id);

        return $this->json([
            'data' => [

            ]
        ]);
    }
}
