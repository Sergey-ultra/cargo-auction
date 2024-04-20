<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Factory;

use App\Modules\User\Domain\Entity\Phone;
use App\Modules\User\Domain\Entity\User;
use App\Modules\User\Infrastructure\DTO\UserPayloadDTO;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    private Generator $faker;
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create();
    }

    public function create(UserPayloadDTO $payload): User
    {
        $phone = new Phone();
        $phone->setPhone($payload->phone);

        $user = new User();
        $user
            ->setPhone($phone)
            ->setName($payload->name)
            ->setEmail($payload->email)
            ->setRoles([$payload->role]);

        $phone->setUser($user);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $payload->password)
        );

        return $user;
    }

    public function createRandomUserByNameAndCompanyId(string $name, int $companyId): User
    {
        $user = new User();
        $user
            ->setName($name)
            ->setEmail($this->faker->email)
            ->setCompanyId($companyId)
            ->setRoles([]);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $this->faker->password)
        );

        return $user;
    }
}
