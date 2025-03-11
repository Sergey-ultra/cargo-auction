<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Modules\Company\Domain\Entity\Company;
use App\Modules\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DUpdateUsersFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'update_user';

    public function getDependencies(): array
    {
        return [
            CCompanyFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = $this->getReference(AUserFixtures::REFERENCE . '_' . $i);
            $this->addReference(self::REFERENCE .'_'. $i, $user);

            if (!$user->getCompanyId()) {
                $key = ($i % 5) + 1;
                /** @var Company $company */
                $company = $this->getReference(CCompanyFixtures::REFERENCE . '_' . $key);
                $user->setCompanyId($company->getId());
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
