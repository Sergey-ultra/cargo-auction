<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Api;

use App\ApiGateway\DTO\CommentDTO;
use App\ApiGateway\DTO\CommentShowDTO;
use App\ApiGateway\DTO\CompanyShowDTO;
use App\ApiGateway\DTO\CompanyWithContactsDTO;
use App\ApiGateway\DTO\ContactDTO;
use App\ApiGateway\DTO\ListDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\RatingDTO;
use App\ApiGateway\DTO\TransportDTO;
use App\Modules\Chat\Infrastructure\Adapter\UserAdapter;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\Load\Infrastructure\Adapter\CityAdapter;
use App\Modules\Transport\Domain\Entity\Comment;
use App\Modules\Transport\Domain\Entity\Transport;
use App\Modules\Transport\Domain\Repository\TransportRepositoryInterface;
use App\Modules\Transport\Infrastructure\Adapter\CompanyAdapter;
use App\Modules\Transport\Infrastructure\DTO\FilterDTO;
use App\Modules\Transport\Infrastructure\Repository\CommentRepository;
use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use App\Modules\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class TransportApi
{
    public function __construct(
        private TransportRepository $transportRepository,
        private CommentApi $commentApi,
        private CityAdapter $cityAdapter,
        private CompanyAdapter $companyAdapter,
        private UserAdapter $userAdapter,
    )
    {
    }

    public function getLoadDraftMessageById(int $id): string
    {
        $transport = $this->transportRepository->find($id);
        return sprintf(
            "По транспорту: %s - %s, %d, %s, %d т., %d м3",
            $transport->getFromName(),
            $transport->getToName(),
            34,
            $transport->getBodyType(),
            $transport->getWeight(),
            $transport->getVolume()
        );
    }

    public function getDefaultOrderByOption(): string
    {
        return TransportRepositoryInterface::CREATED_AT;
    }

    public function getList(
        ?LoadFilter    $filter,
        int            $page = 1,
        int            $perPage = TransportRepositoryInterface::PAGINATOR_PER_PAGE,
        string         $orderOption = TransportRepositoryInterface::CREATED_AT,
        ?int           $commentUserId = null,
        ?UserInterface $byUser = null
    ): ListDTO
    {
        if (isset($filter->fromAddress) && isset($filter->fromRadius)) {
            $fromCity = $this->getCityCoordinatesByCityId($filter, 'fromAddressId', 'fromAddress');
        }

        if (isset($filter->toAddress) && isset($filter->toRadius)) {
            $toCity = $this->getCityCoordinatesByCityId($filter, 'toAddressId', 'toAddress');
        }

        $apiFilter = new FilterDTO(
            isset($fromCity) ? $fromCity->longitude : null,
            isset($fromCity) ? $fromCity->latitude : null,
            $filter->fromRadius ?? null,
            isset($toCity) ? $toCity->longitude : null,
            isset($toCity) ? $toCity->latitude : null,
            $filter->toRadius ?? null,
            $filter->weightMin ?? null,
            $filter->weightMax ?? null,
            $filter->volumeMin ?? null,
            $filter->volumeMax ?? null
        );

        $result =  $this->transportRepository->getList($apiFilter, $page, $perPage, $orderOption, $byUser);

        /** @var int[] $ids */
        $ids = [];
        /** @var int[] $companyIds */
        $companyIds = [];
        /**  @var Transport $item */
        foreach($result->list as $item) {
            $ids[] = $item->getId();
            $companyIds[] = $item->getCompanyId();
        }

        /** @var ArrayCollection<int, CommentShowDTO> $commentCollection */
        $commentCollection = $commentUserId
            ? $this->commentApi->getByLoadIds($ids, $commentUserId)
            : new ArrayCollection();
        /** @var ArrayCollection<int, CompanyShowDTO> $companyCollection */
        $companyCollection = $this->companyAdapter->getByIds($companyIds);
        $userCollection = $this->userAdapter->getByCompanyIds($companyIds);

        $transports = [];
        /**  @var Transport $item */
        foreach($result->list as $item) {
            /**  @var CompanyShowDTO $company */
            $company = $companyCollection->get($item->getCompanyId());
            $userContacts = $userCollection->get($item->getCompanyId());
            $comment = $commentCollection->get($item->getId());

            $companyCity = null !== $company->cityId
                ? $this->cityAdapter->getCityById($company->cityId)
                : null;


            $contacts = [];
            /**  @var User $userContact  */
            foreach($userContacts as $userContact) {
                $contacts[] = new ContactDTO(
                    $userContact->getId(),
                    $userContact->getName(),
                    $userContact->getPhone()->getPhone(),
                    $userContact->getPhone()->getMobilePhone(),
                    $userContact->getEmail(),
                );
            }

            $transports[] = new TransportDTO(
                $item->getId(),
                $item->getFromName(),
                $item->getToName(),
                $item->getBodyTypeName(),
                $item->getWeight(),
                $item->getVolume(),
                $item->getPriceWithoutTax(),
                $item->getPriceWithTax(),
                $item->getPriceCash(),
                $comment,
                $item->getCreatedAt(),
                $item->getUpdatedAt(),
                new CompanyWithContactsDTO(
                    $company->id,
                    $company->fullName,
                    isset($companyCity) ? $companyCity->name : '',
                    $company->type,
                    new RatingDTO(
                        5
                    ),
                    $contacts,
                )
            );
        }

        return new ListDTO($transports, $result->totalCount);
    }

    private function getCityCoordinatesByCityId(LoadFilter $filter, string $cityIdKey, string $address): ?CityCoordinatesDTO
    {
        if (isset($filter->{$cityIdKey})) {
            $city = $this->cityAdapter->getCityCoordinatesByCityId($filter->{$cityIdKey});
        } else {
            $city = $this->cityAdapter->getCityCoordinatesByCityByName($filter->{$address});
        }
        return $city;
    }
}
