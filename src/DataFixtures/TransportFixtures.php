<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\Modules\Transport\Domain\Entity\Transport;
use App\ValueObject\Point;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class TransportFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private ObjectManager $manager;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadData();
    }

    private function loadData(): void
    {
        for ($i = 1; $i < 1000; $i++) {

            $user = $i < 15
                ? $this->getReference(UserFixtures::USER_REFERENCE .'_0')
                : $this->getReference(UserFixtures::USER_REFERENCE .'_'.$this->faker->numberBetween(0, 99));

            $transport = new Transport();

            $fromLongitude = $this->faker->randomFloat(6, 27, 177);
            $fromLatitude = $this->faker->latitude;
            $toLongitude = $this->faker->randomFloat(6, 41, 71);
            $toLatitude = $this->faker->latitude;


            $transport
                ->setFromAddress($this->faker->streetAddress)
                ->setFromLongitude($fromLongitude)
                ->setFromLatitude($fromLatitude)
                ->setFromPoint(new Point($fromLongitude, $fromLatitude))
                ->setToAddress($this->faker->streetAddress)
                ->setToLongitude($toLongitude)
                ->setToLatitude($toLatitude)
                ->setToPoint(new Point($toLongitude, $toLatitude))
                ->setWeight($this->faker->randomFloat('1', 1, 25))
                ->setVolume($this->faker->randomFloat('1', 1, 59))
                ->setPriceWithoutTax($this->faker->numberBetween(100, 200000))
                ->setPriceWithTax($this->faker->numberBetween(100, 200000))
                ->setPriceCash($this->faker->numberBetween(100, 200000))
                ->setBodyType($this->faker->numberBetween(0, count(BodyType::BODY_TYPES) -1))
                ->setDownloadingType($this->faker->numberBetween(0, count(LoadingType::LOADING_TYPES) -1))
                ->setUser($user)
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->manager->persist($transport);
        }
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
