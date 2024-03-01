<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Api;

use App\Modules\User\Domain\Entity\Phone;
use App\Modules\User\Domain\Entity\User;
use App\Modules\User\Domain\Factory\UserFactory;
use App\Modules\User\Infrastructure\DTO\UserPayloadDTO;
use App\Modules\User\Infrastructure\Repository\PhoneRepository;
use App\Modules\User\Infrastructure\Repository\UserRepository;

final readonly class UserApi
{
    public function __construct(
        private UserRepository $userRepository,
        private PhoneRepository $phoneRepository,
        private UserFactory $factory,
    )
    {
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    public function save(UserPayloadDTO $payload): void
    {
        $user = $this->factory->create($payload);
        $this->userRepository->save($user);
    }

    public function saveRandomUser(string $name, int $companyId, ?string $phoneString, ?string $mobilePhoneString): void
    {
        $user = $this->userRepository->findOneBy(['name' => $name, 'companyId' => $companyId]);
        if (null === $user) {
            $user = $this->factory->createRandomUserByNameAndCompanyId($name, $companyId);
            $this->userRepository->save($user);

            $phone = new Phone();
            $phone->setPhone($phoneString)->setMobilePhone($mobilePhoneString)->setUser($user);
            $this->phoneRepository->save($phone);
        }
    }
}
