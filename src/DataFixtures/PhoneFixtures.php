<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\User\Domain\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PhoneFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;


    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 200; $i++) {
            $user = $this->getReference(UserFixtures::USER_REFERENCE .'_'.($i % 100));

            $phone = (new Phone())->setPhone($this->faker->phoneNumber)->setUser($user);

            $manager->persist($phone);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
