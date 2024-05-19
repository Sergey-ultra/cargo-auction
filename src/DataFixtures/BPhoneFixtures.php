<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\User\Domain\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BPhoneFixtures extends Fixture implements DependentFixtureInterface
{
    const PHONES = [
        '+79774785300',
        '+79774785301',
        '+79774785302',
        '+79774785303',
        '+79774785304',
        '+79774785305',
        '+79774785306',
        '+79774785307',
        '+79774785308',
        '+79774785309',
    ];
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = $this->getReference(AUserFixtures::REFERENCE . '_' . $i);

            $phone = (new Phone())
                ->setPhone($this->faker->randomElement(self::PHONES))
                ->setMobilePhone($this->faker->randomElement(self::PHONES))
                ->setUser($user);

            $manager->persist($phone);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AUserFixtures::class,
        ];
    }
}
