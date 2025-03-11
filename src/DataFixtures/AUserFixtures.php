<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AUserFixtures extends Fixture
{
    public const REFERENCE = 'user';

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
            ->setRoles(['owner', 'expeditor'])
            ->setPassword($this->userPasswordHasher->hashPassword($user, '12345678'))
        ;

        $this->addReference(self::REFERENCE . '_1', $user);
        $manager->persist($user);

        for ($i = 2; $i <= 10; $i++) {
            $user = (new User())
                ->setEmail($this->faker->email)
                ->setName($this->faker->name)
                ->setRoles($this->faker->randomElements(['owner', 'expeditor', 'carrier'], 2))
                ->setPassword($this->userPasswordHasher->hashPassword($user, '12345678'))
            ;

            $this->addReference(self::REFERENCE .'_'. $i, $user);
            $manager->persist($user);
        }

        $manager->persist($user);
        $manager->flush();
    }
}
