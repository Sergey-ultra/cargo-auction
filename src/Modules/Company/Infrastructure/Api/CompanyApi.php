<?php

declare(strict_types=1);

namespace App\Modules\Company\Infrastructure\Api;

use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Company\Domain\Repository\CompanyRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class CompanyApi
{
    public function __construct(private CompanyRepositoryInterface $companyRepository)
    {
    }

    public function findByUser(UserInterface $user): ?Company
    {
        return $this->companyRepository->findOneBy(['user' => $user]);
    }

    public function saveCompany(string $name, string $description, UserInterface $user): Company
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (null === $company) {
            $company = new Company();
        }

        $company->setName($name);
        $company->setDescription($description);
        $company->setUser($user);
        $company->setUpdatedAt();

        $this->companyRepository->save($company);
        return $company;
    }
}
