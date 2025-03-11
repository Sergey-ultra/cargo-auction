<?php

declare(strict_types=1);

namespace App\Modules\Transport\Application\Command;

use App\ApiGateway\DTO\CompanySaveDTO;
use App\Modules\Company\Infrastructure\Api\CompanyApi;
use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Transport\Domain\Entity\Transport;
use App\Modules\Transport\Infrastructure\DTO\CityParsingDTO;
use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use App\Modules\User\Infrastructure\Api\UserApi;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'parse:transport',
    description: 'parse transport'
)]
final class ParseTransportCommand extends Command
{
    private array $rawData;
    private array $trucks = [];
    private ArrayCollection $cities;
    private ArrayCollection $companies;
    private array $cityIds = [];
    public function __construct(
        private string $transportScriptString,
        private string $cityScriptString,
        private readonly TransportRepository $transportRepository,
        private readonly LoggerInterface $logger,
        private CompanyApi $companyApi,
        private UserApi $userApi,
    )
    {
        parent::__construct();
        $this->cities = new ArrayCollection();
        $this->companies = new ArrayCollection();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $start = microtime(true);
            $output->writeln('<info>Старт скрипта</info>');

            $this->fillRawData();
            $this->handleRawData();
            $this->insertInfo();

        } catch (\Exception $e) {
            $output->writeln('Failed to parse transport');
            $output->writeln($e->getMessage());
            $this->logger->info($e);
            return Command::FAILURE;
        }

        $duration = microtime(true) - $start;
        $output->writeln("<info>Завершено за $duration секунд</info>");
        return Command::SUCCESS;
    }

    private function fillRawData(): void
    {
        $transportScriptString = $this->transportScriptString . "{\"page\":1,\"items_per_page\":10,\"filter\":{\"dates\":{\"date_from\":\"2024-02-29\",\"date_option\":\"today-plus\"},\"from\":{\"id\":1,\"type\":0,\"exact_only\":true},\"to\":{\"id\":1,\"type\":0,\"exact_only\":true},\"with_rate\":false}}'";
        //sleep(rand(30, 190));

        $result = shell_exec($transportScriptString);

        if (!$result) {
            throw new Exception('Результат скрипта - отсутствует');
        }

        $this->rawData = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    private function handleRawData(): void
    {
        $accounts = $this->rawData['accounts'];

        foreach ($accounts as $atiId => $account) {
            $companyPayload = new CompanySaveDTO(
                $account['firm_name'],
                $account['ownership_id'],
                $account['firm_type_id'],
                null,
                null
            );

            $company = $this->companyApi->saveCompany($companyPayload);

            $this->companies->set($account['ati_id'], $company);

            foreach($account['contacts'] as $contact) {
                $this->userApi->saveRandomUser(
                    $contact['name'],
                    $company->getId(),
                    $contact['telephone'],
                    $contact['mobile']
                );
            }
        }

        foreach ($this->rawData['trucks'] as $truck) {
            if (isset($truck['ati_id']) && isset($accounts[$truck['ati_id']])) {
                $this->trucks[] = $truck;
                $this->cityIds[] = new CityParsingDTO($truck['loading']['city_id'], 2);
                foreach ($truck['unloadings'] as $unloadingItem) {
                    if ($unloadingItem['point_type'] !== 0) {
                        $this->cityIds[] = new CityParsingDTO($unloadingItem['point_id'], $unloadingItem['point_type']);
                    }
                }
            }
        }

        $this->loadCityNames();
    }

    private function loadCityNames(): void
    {
        $bodyJson = json_encode($this->cityIds);
        $cityCurlString = $this->cityScriptString . "$bodyJson'";

        $result = shell_exec($cityCurlString);

        if (!$result) {
            throw new Exception('Результат скрипта - отсутствует');
        }

        $cities = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        foreach ($cities['cities'] as $city) {
            $this->cities->set($city['city_id'], $city['name']);
        }
        $this->cities->set(1, 'Россия');
    }

    private function insertInfo(): void
    {
        $count = 0;

        foreach ($this->trucks as $truck) {
            $companyId = $this->companies->get($truck['ati_id'])->getId();
            $fromName = $this->cities->get($truck['loading']['city_id']);
            $toName = $this->cities->get($truck['unloading_list'][0]['point_id']);

            if ($companyId && $fromName && $toName) {

                $transport = $this->transportRepository->findOneBy([
                    'companyId' => $companyId,
                    'fromName' => $fromName,
                    'toName' => $toName,
                ]);

                $transportData = $truck['transport'];
                $bodyType = $this->getBodyType($transportData['car_type']);

                if (null === $transport && $bodyType) {
                    $transport = new Transport();
                    $transport
                        ->setCompanyId($companyId)
                        ->setFromName($fromName)
                        ->setToName($toName)
                        ->setWeight($transportData['weight'])
                        ->setVolume($transportData['volume'])
                        ->setBodyType($bodyType)
                        ->setPriceCash((int)$truck['payment']['cash_sum'])
                        ->setPriceWithoutTax((int)$truck['payment']['sum_without_nds'])
                        ->setPriceWithTax((int)$truck['payment']['sum_with_nds'])
                        ->setCreatedAt();

                    $this->transportRepository->save($transport);
                    ++$count;
                }
            }
        }

        $this->logger->info(sprintf("Вставлено строк %d транспорта", $count));
    }

    private function getBodyType(int $carType): ?int
    {
        $bodyTypeKey = array_search($carType, array_column(BodyType::TYPES, 'dictionary_item_id'));

        if (false !== $bodyTypeKey) {
            return (int)$bodyTypeKey;
        }
        return null;
    }
}
