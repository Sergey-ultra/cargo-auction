<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\User\Domain\Entity\User;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        $user = (new User());

        $user
            ->setEmail('maasa@list.ru')
            ->setName('Morozov Sergey')
            ->setPassword(
                $this->userPasswordHasher->hashPassword($user, '12345678')
            );

        $this->addReference(self::USER_REFERENCE . '_0', $user);
        $manager->persist($user);

        for ($i = 1; $i < 100; $i++) {
            $user = (new User());
            $user
                ->setEmail($this->faker->email)
                ->setName($this->faker->name)
                ->setPassword($this->userPasswordHasher->hashPassword($user, '12345678'));

            $this->addReference(self::USER_REFERENCE .'_'. $i, $user);
            $manager->persist($user);
        }

        $manager->persist($user);
        $manager->flush();


    }
}
