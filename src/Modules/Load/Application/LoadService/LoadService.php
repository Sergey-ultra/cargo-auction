<?php

declare(strict_types=1);

namespace App\Modules\Load\Application\LoadService;

use App\Modules\City\Infrastructure\DTO\CityDTO;
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

    public function save(Load $load, CityDTO $fromCity, CityDTO $toCity): void
    {
        $fromFullAddress = sprintf("%s %s", $fromCity->name, $load->getFromAddress());
        $fromPoint = $this->geoCoderService->getMapPoint($fromFullAddress);

        if ($fromPoint) {
            $load->setFromLatitude($fromPoint->getLatitude())
                ->setFromLongitude($fromPoint->getLongitude())
                ->setFromPoint(new Point($fromPoint->getLatitude(), $fromPoint->getLongitude()));
        } else {
            $load->setFromLatitude($fromCity->latitude)
                ->setFromLongitude($fromCity->longitude)
                ->setFromPoint(new Point($fromCity->latitude, $fromCity->longitude));
        }

        $toFullAddress = sprintf("%s %s", $toCity->name, $load->getToAddress());
        $toPoint = $this->geoCoderService->getMapPoint($toFullAddress);

        if ($toPoint) {
            $load->setToLatitude($toPoint->getLatitude())
                ->setToLongitude($toPoint->getLongitude())
                ->setToPoint(new Point($toPoint->getLatitude(), $toPoint->getLongitude()));
        } else {
            $load->setToLatitude($toCity->latitude)
                ->setToLongitude($toCity->longitude)
                ->setToPoint(new Point($toCity->latitude, $toCity->longitude));
        }

        $this->repository->save($load);
    }
}
