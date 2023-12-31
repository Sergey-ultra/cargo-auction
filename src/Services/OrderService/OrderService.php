<?php

declare(strict_types=1);

namespace App\Services\OrderService;

use App\Entity\Load;
use App\Repository\LoadRepository;
use App\Services\GeoCoderService\GeoCoderService;
use App\ValueObject\Point;

class OrderService
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
