<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\CompanySaveDTO;
use App\Modules\Company\Infrastructure\Api\CompanyApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CompanyController extends ApiController
{
    #[Route('/company', name: 'company.my.save', methods:['post'])]
    public function save(#[MapRequestPayload]CompanySaveDTO $payload, CompanyApi $companyApi): JsonResponse
    {
        $company = $companyApi->saveCompany($payload, $this->getUser());

        return $this->apiJson(['data' => $company->getId()], Response::HTTP_CREATED);
    }

    #[Route('/company/my', name: 'company.my.index', methods:['get'])]
    public function my(CompanyApi $companyApi): JsonResponse
    {
        $myCompany = $companyApi->findByUser($this->getUser());

        return $this->apiJson(['data' => $myCompany]);
    }
}
