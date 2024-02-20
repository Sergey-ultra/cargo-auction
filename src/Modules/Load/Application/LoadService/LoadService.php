<?php

declare(strict_types=1);

namespace App\Modules\Load\Application\LoadService;

use App\Modules\Load\Application\GeoCoderService\GeoCoderService;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use App\ValueObject\Point;


final readonly class LoadService
{
    public function __construct(
        private LoadRepository  $repository,
        private GeoCoderService $geoCoderService
    ) {
    }

    public function save(Load $load): void
    {
        $fromPoint = $this->geoCoderService->getMapPoint($load->getFromAddress());

        if ($fromPoint) {
            $load->setFromLatitude($fromPoint->getLatitude())
                ->setFromLongitude($fromPoint->getLongitude())
                ->setFromPoint(new Point($fromPoint->getLatitude(), $fromPoint->getLongitude()));
        }

        $toPoint = $this->geoCoderService->getMapPoint($load->getToAddress());

        if ($toPoint) {
            $load->setToLatitude($toPoint->getLatitude())
                ->setToLongitude($toPoint->getLongitude())
                ->setToPoint(new Point($toPoint->getLatitude(), $toPoint->getLongitude()));

        }

        $this->repository->save($load);
    }
}
