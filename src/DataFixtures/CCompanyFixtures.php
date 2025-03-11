<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Company\Domain\Entity\CompanyType;
use App\Modules\Company\Domain\Entity\Ownership;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CCompanyFixtures extends Fixture
{
    public const REFERENCE = 'company';

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $company = (new Company());
            $company
                ->setName($this->faker->company)
                ->setTypeId($this->faker->randomElement(array_keys(CompanyType::COMPANY_TYPES)))
                ->setOwnershipId($this->faker->randomElement(array_keys(Ownership::OWNERSHIP_NAMES)))
                ->setDescription('Text')
                ->setUser($this->getReference(AUserFixtures::REFERENCE . '_' . $i))
                ;

            $this->addReference(self::REFERENCE .'_'. $i, $company);
            $manager->persist($company);
        }

        $manager->persist($company);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AUserFixtures::class,
        ];
    }
}
