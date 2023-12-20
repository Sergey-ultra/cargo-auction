<?php

declare(strict_types=1);

namespace App\Services\OrderService;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Services\GeoCoderService\GeoCoderService;
use App\ValueObject\Point;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $repository,
        private readonly GeoCoderService $geoCoderService
    ){}
    public function save(Order $order): void
    {
        $fromPoint = $this->geoCoderService->getMapPoint($order->getFromAddress());

        if ($fromPoint) {
            $order->setFromLatitude($fromPoint->getLatitude())
                ->setFromLongitude($fromPoint->getLongitude());
        }

        $toPoint = $this->geoCoderService->getMapPoint($order->getToAddress());

        if ($toPoint) {
            $order->setToLatitude($toPoint->getLatitude())
                ->setToLongitude($toPoint->getLongitude());
        }

        $this->repository->save($order);
    }
}
