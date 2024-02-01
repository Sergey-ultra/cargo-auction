<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\Load;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Chat>
 *
 *
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function getMyChats(UserInterface $owner, int $perPage)
    {
        return $this->findBy(['owner' => $owner], null, $perPage);
    }

    public function getByUserId(UserInterface $owner, UserInterface $partner, Load $load): Chat
    {
        if (! ($existing = $this->findOneBy(['owner' => $owner, 'partner' => $partner]))) {
            $existing = new Chat();
            $existing
                ->setName($partner->getName())
                ->setDescription($partner->getName())
                ->setDraft(
                    sprintf("По грузу: %s - %s, %d, %s, %d т., %d м3",
                        $load->getFromAddress(),
                        $load->getToAddress(),
                        34,
                        $load->getCargoType(),
                        $load->getWeight(),
                        $load->getVolume()
                    )
                )
                ->setOwner($owner)
                ->setPartner($partner)
            ;

            $this->_em->persist($existing);
            $this->_em->flush();

        }
        return $existing;
    }
}
