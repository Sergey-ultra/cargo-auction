<?php

declare(strict_types=1);

namespace App\Modules\Company\Infrastructure\Api;

use App\ApiGateway\DTO\CompanySaveDTO;
use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Company\Domain\Repository\CompanyRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function getByIds(array $ids): ArrayCollection
    {
        $companies = $this->companyRepository->findBy(['id' => $ids]);
        $collection = new ArrayCollection();

        foreach($companies as $company) {
            $collection->set($company->getId(), $company);
        }

        return $collection;
    }

    public function saveCompany(CompanySaveDTO $payload, ?UserInterface $user = null): Company
    {
        $company = $this->companyRepository->findOneBy(['user' => $user, 'name' => $payload->name]);

        if (null === $company) {
            $company = new Company();
            $company
                ->setName($payload->name)
                ->setUser($user);
        }

        $company
            ->setDescription($payload->description)
            ->setOwnershipId($payload->ownershipId)
            ->setTypeId($payload->typeId)
            ->setUpdatedAt();

        $this->companyRepository->save($company);
        return $company;
    }
}
