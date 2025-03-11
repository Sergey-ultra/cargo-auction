<?php

declare(strict_types=1);

namespace App\Modules\City\Application\Command;

use App\Modules\City\Infrastructure\Repository\CityRepository;
use LimitIterator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'load:cities',
    description: 'Load info to cities table',
)]
class LoadCities extends Command
{
    private CityRepository $repository;
    protected string $projectDir;

    public function __construct(KernelInterface $kernel, CityRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->projectDir = $kernel->getProjectDir();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $start = microtime(true);
            $output->writeln('<info>Старт скрипта</info>');
            $this->repository->truncate();
            $this->repository->patchInsert($this->readFile());
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to load data to cities table</error>');
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $duration = microtime(true) - $start;
        $output->writeln("<info>Завершено за $duration секунд</info>");
        return Command::SUCCESS;
    }

    /**
     * @return iterable<array{
     *     id: string,
     *     name: string,
     *     other_name: string,
     *     region_name: string,
     *     district: string,
     *     lon: string,
     *     lat: string,
     *     population: string,
     *     approx: string
     * }>
     */
    protected function readFile(): iterable
    {
        $filePath = sprintf('%s/%s', $this->projectDir, 'RussianOikonymsDataset.tsv');

        $spl = new \SplFileObject($filePath, 'r');

        $spl->setFlags(\SplFileObject::READ_CSV);
        $spl->setCsvControl(chr(9));
        $spl->setMaxLineLen(0);

        $lines = new LimitIterator($spl, 1);

        foreach ($lines AS $array) {
            yield $array;
        }
    }
}
