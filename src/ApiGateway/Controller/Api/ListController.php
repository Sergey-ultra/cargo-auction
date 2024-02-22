<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\ListQuery;
use App\ApiGateway\DTO\LoadFilter;
use App\Modules\Load\Infrastructure\Api\LoadApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ListController extends AbstractController
{
    #[Route('/list', name: 'api.list', methods:['get'])]
    public function index(#[MapQueryString] ?ListQuery $listQuery, LoadApi $loadApi): JsonResponse
    {
        $result = [];

        if (null !== $listQuery) {
            if (in_array('load', $listQuery->parameters, true)) {
                $result = [
                    'loadOptions' => $loadApi->getLoadOption(),
                    'perPageOptions' => [10, 20, 30, 50, 100]
                ];
            }

            if (in_array('load-form', $listQuery->parameters, true)) {
                $result = [
                    'cargoTypes' => $loadApi->getCargoTypes(),
                    'packageTypes' => $loadApi->getPackageType(),
                    'bodyTypes' => $loadApi->getBodyTypes(),
                    'loadingTypes' => $loadApi->getLoadingTypes(),
                    'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
                    'priceTypes' => $loadApi->getPriceTypes(),
                ];
            }
        }

        return $this->json(['data' => $result]);
    }
}
