<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Repository;

use App\Modules\Chat\Domain\Entity\Chat;
use App\Modules\Chat\Domain\Repository\ChatRepositoryInterface;
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
class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /** @return Chat[] */
    public function getMyChats(UserInterface $owner, int $perPage): array
    {
        return $this->findBy(['owner' => $owner], null, $perPage);
    }

    public function getByUserId(UserInterface $owner, UserInterface $partner, string $draftMessage): Chat
    {
        $existing = $this->findOneBy(['owner' => $owner, 'partner' => $partner]);

        if (! $existing) {
            $existing = new Chat();
            $existing
                ->setName($partner->getName())
                ->setDescription($partner->getName())
                ->setDraft($draftMessage)
                ->setOwner($owner)
                ->setPartner($partner)
                ->setCreatedAt();

            $this->_em->persist($existing);
            $this->_em->flush();

        }
        return $existing;
    }
}
