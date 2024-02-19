<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\ValueObject\Point;
use BodyType;
use CargoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Load;
use LoadingType;

class LoadFixtures extends Fixture implements DependentFixtureInterface
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
        $this->loadOrders();
    }

    private function loadOrders(): void
    {
        for ($i = 1; $i < 1000; $i++) {

            $user = $i < 15
                ? $this->getReference(UserFixtures::USER_REFERENCE .'_0')
                : $this->getReference(UserFixtures::USER_REFERENCE .'_'.$this->faker->numberBetween(0, 99));

            $order = new Load();

            $fromLongitude = $this->faker->randomFloat(6, 27, 177);
            $fromLatitude = $this->faker->latitude;
            $toLongitude = $this->faker->randomFloat(6, 41, 71);
            $toLatitude = $this->faker->latitude;


            $order
                ->setDownloadingDateStatus($this->faker->randomElement(array_filter(Load::DOWNLOADING_DATE_STATUSES, fn($el) => $el !== 'request')))
                ->setDownloadingDate($this->faker->dateTimeBetween('now','6 days'))
                ->setFromAddress($this->faker->streetAddress)
                ->setFromLongitude($fromLongitude)
                ->setFromLatitude($fromLatitude)
                ->setFromPoint(new Point($fromLongitude, $fromLatitude))
                ->setToAddress($this->faker->streetAddress)
                ->setToLongitude($toLongitude)
                ->setToLatitude($toLatitude)
                ->setToPoint(new Point($toLongitude, $toLatitude))
                ->setWeight((string)$this->faker->randomFloat('1', 1, 25))
                ->setVolume((string)$this->faker->randomFloat('1', 1, 59))
                ->setPriceType($this->faker->randomElement(load::PRICE_TYPE))
                ->setPriceWithoutTax($this->faker->numberBetween(100, 200000))
                ->setPriceWithTax($this->faker->numberBetween(100, 200000))
                ->setPriceCash($this->faker->numberBetween(100, 200000))
                ->setCargoType($this->faker->numberBetween(0, count(CargoType::CARGO_TYPES) - 1))
                ->setBodyType($this->faker->numberBetween(0, count(BodyType::BODY_TYPES) -1))
                ->setDownloadingType($this->faker->numberBetween(0, count(LoadingType::LOADING_TYPES) -1))
                ->setUnloadingType($this->faker->numberBetween(0, count(LoadingType::LOADING_TYPES) -1))
                ->setUser($user)
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->manager->persist($order);
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
