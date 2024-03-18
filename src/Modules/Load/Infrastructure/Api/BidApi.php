<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\BidDTO;
use App\ApiGateway\DTO\WebSocketNotification;
use App\Modules\Load\Domain\Entity\Bid;
use App\Modules\Load\Infrastructure\Repository\BidRepository;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use Fresh\CentrifugoBundle\Service\CentrifugoInterface;

final readonly class BidApi
{
    public function __construct(
        private LoadRepository $loadRepository,
        private BidRepository $bidRepository,
        private CentrifugoInterface $centrifugo
    )
    {
    }

    public function saveBid(int $bidValue, int $loadId): BidDTO
    {
        $load = $this->loadRepository->find($loadId);

        $bid = new Bid();
        $bid->setLoad($load);
        $bid->setBid($bidValue);


        $this->bidRepository->save($bid);

        $message = sprintf("На вашу заявку поставили ставку в размере %d %s", $bidValue, 'руб');
        $loadUser = $bid->getLoad()->getUser();

        $notification = new WebSocketNotification($message, $loadUser->getId());
        $channel = sprintf("notification_%s", $loadUser->getId());
        try {
            $this->centrifugo->publish($notification->toArray(), $channel);
        } catch (\Throwable $e) {

        }

        return new BidDTO($bid->getId(), $bid->getBid());
    }
}
