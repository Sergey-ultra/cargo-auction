<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Api;

use App\Modules\User\Domain\Entity\Phone;
use App\Modules\User\Domain\Entity\User;
use App\Modules\User\Domain\Factory\UserFactory;
use App\Modules\User\Infrastructure\DTO\UserPayloadDTO;
use App\Modules\User\Infrastructure\Repository\PhoneRepository;
use App\Modules\User\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

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

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param int $companyId
     * @return User[]
     */
    public function getByCompanyId(int $companyId): array
    {
        return $this->userRepository->findBy(['companyId' => $companyId]);
    }

    /** @param int[] $ids */
    public function getByCompanyIds(array $ids): ArrayCollection
    {
        $users = $this->userRepository->findBy(['companyId' => $ids]);
        $collection = new ArrayCollection();

        foreach($users as $user) {
            $array = $collection->get($user->getCompanyId());
            if (!$array) {
                $array = [];
            }
            $array[] = $user;
            $collection->set($user->getCompanyId(), $array);
        }

        return $collection;
    }

    public function save(UserPayloadDTO $payload): User
    {
        $user = $this->factory->create($payload);
        $this->userRepository->save($user);
        return $user;
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
