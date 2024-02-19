<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\Modules\Load\Infrastructure\Api\LoadApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ListController extends AbstractController
{
    #[Route('/list', name: 'api.list', methods:['get'])]
    public function index(LoadApi $loadApi): JsonResponse
    {
        $perPageOptions = [10, 20, 30, 50, 100];

        return $this->json(['data' => [
                'perPageOptions' => $perPageOptions,
                'loadOptions' => $loadApi->getLoadOption(),
            ]]
        );
    }
}
