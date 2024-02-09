<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\CompanySaveDTO;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CompanyController extends ApiController
{
    #[Route('/company', name: 'company.my.save', methods:['post'])]
    public function save(#[MapRequestPayload]CompanySaveDTO $payload, CompanyRepository $repo): JsonResponse
    {
        $user = $this->getUser();

        $company = $repo->findOneBy(['user' => $user]);
        if (null === $company) {
            $company = new Company();
        }

        $company->setName($payload->name);
        $company->setDescription($payload->description);
        $company->setUser($user);
        $company->setUpdatedAt();

        $repo->save($company);
        return $this->apiJson(['data' => $company->getId()], Response::HTTP_CREATED);
    }

    #[Route('/company/my', name: 'company.my.index', methods:['get'])]
    public function my(CompanyRepository $companyRepository): JsonResponse
    {
        $myCompany = $companyRepository->findOneBy(['user' => $this->getUser()]);

        return $this->apiJson(['data' => $myCompany]);
    }
}
