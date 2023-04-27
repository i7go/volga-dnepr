<?php

declare(strict_types=1);

namespace App\Command\Air;

use App\Entity\Air\Aircraft;
use App\Entity\Air\Airports;
use App\Entity\Air\Flights;
use App\Repository\Air\AircraftRepository;
use App\Repository\Air\AirportsRepository;
use App\Service\DataReader\CsvReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImportCommand extends Command
{
    private const DEFAULT_NAME = 'air:import';
    private const FLUSH_COUNT = 100;

    public function __construct(
        private ParameterBagInterface $params,
        private EntityManagerInterface $em,
        private AircraftRepository $repoAircraft,
        private AirportsRepository $repoAirports,
    ) {
        $name = self::DEFAULT_NAME;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Загрузка данных в таблицы')
            ->setHelp('Выполнить загрузку данных в таблицы')
            ->addArgument('path', InputArgument::REQUIRED, 'Путь к папке с файлами. Например: ./docs/task')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        try {
            $this->process($output, $path);

            return Command::SUCCESS;
        } catch (CommandException $ex) {
            throw $ex;
        }
    }

    private function process(OutputInterface $output, string $path): void
    {
        if (\in_array($path[0], ['\\', '/'], true)) {
            $dir = $path;
            $root = '.';
        } else {
            $root = $this->params->get('kernel.project_dir');
            $dir = realpath($root.'/'.$path);
        }

        $filesystem = new Filesystem();
        if (false === $dir || !$filesystem->exists($dir)) {
            throw new CommandException("Файловая папка '$path' не найдена в директории '$root'");
        }

        $output->writeln("Сканируем папку '$dir'");

        $this->em->wrapInTransaction(function () use ($output, $dir): void {
            $this->truncateFlights($output);
            $this->truncateAircraft($output);
            $this->truncateAirports($output);

            $this->loadAircraft($output, $dir);
            $this->loadAirports($output, $dir);
            $this->loadFlights($output, $dir);
        });
    }

    private function truncateAircraft(OutputInterface $output): void
    {
        $output->writeln('truncateAircraft()');

        $this->em->createQueryBuilder()
            ->delete(Aircraft::class, 'f')
            ->where('f.id = f.id')
            ->getQuery()
            ->execute()
        ;
    }

    private function loadAircraft(OutputInterface $output, string $path): void
    {
        $output->writeln('loadAircraft()');

        $csv = new CsvReader();
        $csv->open($path.'/aircraft.csv');

        $header = $csv->getHeader(); // Получим названия полей
        $cnt = 0;

        /** @var string[] $row */
        foreach ($csv->getRecords($header) as $row) {
            $obj = new Aircraft();
            $obj->setId((int) $row['id']);
            $obj->setTail($row['tail']);

            $this->em->persist($obj);
            if ($cnt++ >= self::FLUSH_COUNT) {
                $this->em->flush();
            }
        }

        $this->em->flush();
    }

    private function truncateAirports(OutputInterface $output): void
    {
        $output->writeln('truncateAirports()');
        $this->em->createQueryBuilder()
            ->delete(Airports::class, 'f')
            ->where('f.id = f.id')
            ->getQuery()
            ->execute()
        ;
    }

    private function loadAirports(OutputInterface $output, string $path): void
    {
        $output->writeln('loadAirports()');

        $csv = new CsvReader();
        $csv->open($path.'/airports.csv');

        $header = $csv->getHeader(); // Получим названия полей
        $cnt = 0;

        /** @var string[] $row */
        foreach ($csv->getRecords($header) as $row) {
            $obj = new Airports();
            $obj->setId((int) $row['id']);
            $obj->setCodeIata($row['code_iata']);
            $obj->setCodeIcao($row['code_icao']);
            $obj->setCountry($row['country']);
            $obj->setMunicipality($row['municipality']);
            $obj->setName($row['name']);

            $this->em->persist($obj);
            if ($cnt++ >= self::FLUSH_COUNT) {
                $this->em->flush();
            }
        }

        $this->em->flush();
    }

    private function truncateFlights(OutputInterface $output): void
    {
        $output->writeln('truncateFlights()');
        $this->em->createQueryBuilder()
            ->delete(Flights::class, 'f')
            ->where('f.id = f.id')
            ->getQuery()
            ->execute()
        ;
    }

    private function loadFlights(OutputInterface $output, string $path): void
    {
        $output->writeln('loadFlights()');

        $dateTimeZone = new \DateTimeZone(date_default_timezone_get());

        $csv = new CsvReader();
        $csv->open($path.'/flights.csv');

        $header = $csv->getHeader(); // Получим названия полей
        $cnt = 0;

        /** @var string[] $row */
        foreach ($csv->getRecords($header) as $row) {
            $obj = new Flights();
            $obj->setId((int) $row['id']);

            $aircraft = $this->repoAircraft->find((int) $row['aircraft_id']);
            if (null === $aircraft) {
                throw new CommandException("Не найдено воздушное судно ID={$row['aircraft_id']}");
            }
            $obj->setAircraft($aircraft);

            $airport1 = $this->repoAirports->find((int) $row['airport_id1']);
            if (null === $airport1) {
                throw new CommandException("Не найден аэропорт ID={$row['airport_id1']}");
            }
            $obj->setAirport1($airport1);

            $airport2 = $this->repoAirports->find((int) $row['airport_id2']);
            if (null === $airport2) {
                throw new CommandException("Не найден аэропорт ID={$row['airport_id2']}");
            }
            $obj->setAirport2($airport2);

            $takeoff = new \DateTime($row['takeoff'], $dateTimeZone);
            $obj->setTakeoff($takeoff);

            $landing = new \DateTime($row['landing'], $dateTimeZone);
            $obj->setLanding($landing);

            $obj->setLoad((int) $row['load']);
            $obj->setOffload((int) $row['offload']);

            $this->em->persist($obj);
            if ($cnt++ >= self::FLUSH_COUNT) {
                $this->em->flush();
            }
        }

        $this->em->flush();
    }
}
