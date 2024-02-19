<?php

declare(strict_types=1);

namespace App\Modules\Load\Application\OrderService;

use App\Modules\Load\Application\GeoCoderService\GeoCoderService;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use App\ValueObject\Point;


class LoadService
{
    public function __construct(
        private readonly LoadRepository  $repository,
        private readonly GeoCoderService $geoCoderService
    ){}
    public function save(Load $order): void
    {
        $fromPoint = $this->geoCoderService->getMapPoint($order->getFromAddress());

        if ($fromPoint) {
            $order->setFromLatitude($fromPoint->getLatitude())
                ->setFromLongitude($fromPoint->getLongitude())
                ->setFromPoint(new Point($fromPoint->getLatitude(), $fromPoint->getLongitude()));
        }

        $toPoint = $this->geoCoderService->getMapPoint($order->getToAddress());

        if ($toPoint) {
            $order->setToLatitude($toPoint->getLatitude())
                ->setToLongitude($toPoint->getLongitude())
                ->setToPoint(new Point($toPoint->getLatitude(), $toPoint->getLongitude()));

        }

        $this->repository->save($order);
    }
}
