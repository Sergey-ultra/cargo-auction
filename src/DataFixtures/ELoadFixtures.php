<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\CargoType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\Modules\User\Domain\Entity\User;
use App\ValueObject\Point;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class ELoadFixtures extends Fixture implements DependentFixtureInterface
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
        $contactIdsCollection = $this->getContactIds();

        for ($i = 1; $i <= 1000; $i++) {

            $user = $i < 15
                ? $this->getReference(DUpdateUsersFixtures::REFERENCE .'_1')
                : $this->getReference(DUpdateUsersFixtures::REFERENCE .'_'.$this->faker->numberBetween(1, 10));

            $order = new Load();

            $fromLongitude = $this->faker->randomFloat(6, 27, 177);
            $fromLatitude = $this->faker->latitude;
            $toLongitude = $this->faker->randomFloat(6, 41, 71);
            $toLatitude = $this->faker->latitude;

            /** @var Company $company */
            $company = $this->getReference(CCompanyFixtures::REFERENCE .'_'.$this->faker->numberBetween(1, 5));

            $contactIds = $contactIdsCollection->get($company->getId());


            $bodyTypes = $this->faker->randomElements(array_column(BodyType::TYPES, 'dictionary_item_id'), 2);
            $loadingTypes = $this->faker->randomElements(array_column(LoadingType::TYPES, 'dictionary_item_id'));
            $unloadingTypes = $this->faker->randomElements(array_column(LoadingType::TYPES, 'dictionary_item_id'));

            $order
                ->setCompanyId($company->getId())
                ->setLoadingType($this->faker->randomElement(
                    array_filter(
                        array_keys(Load::DOWNLOADING_DATE_TITLES),
                        fn(string $el) => $el !== Load::DOWNLOADING_DATE_REQUEST_STATUS)
                    )
                )
                ->setLoadingFirstDate($this->faker->dateTimeBetween('now','6 days'))
                ->setLoadingLastDate($this->faker->dateTimeBetween('now','8 days'))
                ->setLoadingStartTime(new DateTime($this->faker->randomElement(["12:00", '13:00'])))
                ->setLoadingEndTime(new DateTime($this->faker->randomElement(["16:00", "17:00"])))
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
                ->setUnloadingDate($this->faker->dateTimeBetween('now','9 days'))
                ->setUnloadingStartTime(new DateTime($this->faker->randomElement(["12:00", '13:00', '14:00'])))
                ->setUnloadingEndTime(new DateTime($this->faker->randomElement(["16:00", "17:00", '20:00'])))
                ->setWeight($this->faker->randomFloat('1', 1, 25))
                ->setVolume($this->faker->randomFloat('1', 1, 59))
                ->setPriceType($this->faker->randomElement(array_keys(Load::PRICE_TYPE)))
                ->setPriceWithoutTax($this->faker->numberBetween(100, 200000))
                ->setPriceWithTax($this->faker->numberBetween(100, 200000))
                ->setPriceCash($this->faker->numberBetween(100, 200000))
                ->setCargoType($this->faker->numberBetween(0, count(CargoType::CARGO_TYPES) - 1))
                ->setBodyTypes($bodyTypes)
                ->setTruckLoadingTypes($loadingTypes)
                ->setTruckUnloadingTypes($unloadingTypes)
                ->setUser($user)
                ->setContactIds($contactIds)
                ->setNote('-ГОТОВ НА УКАЗАННЫЕ ДАТЫ!- -БЫСТРАЯ ПОГРУЗКА- -БЫСТРАЯ ВЫГРУЗКА БЕЗ ВЫХОДНЫХ!- -ЗВОНИТЕ!-')
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->manager->persist($order);
        }
        $this->manager->flush();
    }

    private function getContactIds(): ArrayCollection
    {
        $contactUsers = $this->manager->getRepository(User::class)->findAll();
        $collection = new ArrayCollection();

        foreach($contactUsers as $user) {
            $array = $collection->get($user->getCompanyId());
            if (!$array) {
                $array = [];
            }
            $array[] = $user->getId();
            $collection->set($user->getCompanyId(), $array);
        }

        return $collection;
    }

    public function getDependencies(): array
    {
        return [
            CCompanyFixtures::class,
            DUpdateUsersFixtures::class,
        ];
    }
}
