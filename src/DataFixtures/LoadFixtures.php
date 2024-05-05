<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\CargoType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\ValueObject\Point;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class LoadFixtures extends Fixture implements DependentFixtureInterface
{
    const CITY_IDS = [155074, 97486, 109644];
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

        for ($i = 1; $i <= 1000; $i++) {

            $user = $i < 15
                ? $this->getReference(UserFixtures::REFERENCE .'_1')
                : $this->getReference(UserFixtures::REFERENCE .'_'.$this->faker->numberBetween(1, 10));

            $order = new Load();

            $fromLongitude = $this->faker->randomFloat(6, 27, 177);
            $fromLatitude = $this->faker->latitude;
            $toLongitude = $this->faker->randomFloat(6, 41, 71);
            $toLatitude = $this->faker->latitude;

            $company = $this->getReference(CompanyFixtures::REFERENCE .'_'.$this->faker->numberBetween(1, 5));

            $bodyTypes = $this->faker->randomElements(array_column(BodyType::TYPES, 'dictionary_item_id'), 2);

            $order
                ->setCompanyId($company->getId())
                ->setDownloadingDateStatus($this->faker->randomElement(
                    array_filter(
                        array_keys(Load::DOWNLOADING_DATE_TITLES),
                        fn(string $el) => $el !== Load::DOWNLOADING_DATE_REQUEST_STATUS)
                    )
                )
                ->setDownloadingDate($this->faker->dateTimeBetween('now','6 days'))
                ->setFromAddress($this->faker->streetAddress)
                ->setFromCityId($this->faker->randomElement(self::CITY_IDS))
                ->setFromLongitude($fromLongitude)
                ->setFromLatitude($fromLatitude)
                ->setFromPoint(new Point($fromLongitude, $fromLatitude))
                ->setToAddress($this->faker->streetAddress)
                ->setToCityId($this->faker->randomElement(self::CITY_IDS))
                ->setToLongitude($toLongitude)
                ->setToLatitude($toLatitude)
                ->setToPoint(new Point($toLongitude, $toLatitude))
                ->setWeight($this->faker->randomFloat('1', 1, 25))
                ->setVolume($this->faker->randomFloat('1', 1, 59))
                ->setPriceType($this->faker->randomElement(array_keys(Load::PRICE_TYPE)))
                ->setPriceWithoutTax($this->faker->numberBetween(100, 200000))
                ->setPriceWithTax($this->faker->numberBetween(100, 200000))
                ->setPriceCash($this->faker->numberBetween(100, 200000))
                ->setCargoType($this->faker->numberBetween(0, count(CargoType::CARGO_TYPES) - 1))
                ->setBodyTypes($bodyTypes)
                ->setDownloadingType($this->faker->numberBetween(0, count(LoadingType::LOADING_TYPES) -1))
                ->setUnloadingType($this->faker->numberBetween(0, count(LoadingType::LOADING_TYPES) -1))
                ->setUser($user)
                ->setNote('-ГОТОВ НА УКАЗАННЫЕ ДАТЫ!- -БЫСТРАЯ ПОГРУЗКА- -БЫСТРАЯ ВЫГРУЗКА БЕЗ ВЫХОДНЫХ!- -ЗВОНИТЕ!-')
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
            CompanyFixtures::class,
        ];
    }
}
