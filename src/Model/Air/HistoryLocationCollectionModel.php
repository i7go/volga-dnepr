<?php

declare(strict_types=1);

namespace App\Model\Air;

use App\Entity\Air\Aircraft;
use App\Entity\Air\Airports;
use App\Entity\Air\Flights;
use App\Entity\Air\HistoryLocation;
use App\Entity\Air\HistoryLocationFilter;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Модель для получения истории нахождения ВС в аэропортах.
 */
class HistoryLocationCollectionModel
{
    private ConstraintViolationListInterface $errors;

    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $em,
    ) {
    }

    public function getValidationError(): string
    {
        /** @var ConstraintViolationInterface */
        $error = $this->errors[0];

        return $error->getPropertyPath().': '.$error->getMessage();
    }

    /**
     * Проверить параметры фильтра.
     */
    public function validateFilter(HistoryLocationFilter $apiParam): bool
    {
        $groups = null;
        $this->errors = $this->validator->validate($apiParam, null, $groups);

        return 0 === \count($this->errors);
    }

    /**
     * @return HistoryLocation[]
     */
    public function findCollectionAllHistoryLocations(HistoryLocationFilter $filter): array
    {
        $builder = $this->em->createQueryBuilder();
        $builder = $builder->select('fx.id')
            ->from(Flights::class, 'fx')
            ->where('fx.aircraft = f1.aircraft')
            ->andWhere('fx.airport1 = f1.airport1')
            ->andWhere('fx.takeoff < f1.takeoff')
            ->andWhere('fx.landing  > f2.landing');

        $query = $this->em->createQueryBuilder()
            ->select([
                'ap.id',
                'ap.codeIata',
                'ap.codeIcao',
                'f2.landing',
                'f2.offload',
                'f1.load',
                'f1.takeoff',
            ])
            ->from(Airports::class, 'ap')
            ->innerJoin(Flights::class, 'f1', Join::WITH, 'ap.id = f1.airport1')
            ->innerJoin(Flights::class, 'f2', Join::WITH, 'ap.id = f2.airport2')
            ->innerJoin(Aircraft::class, 'ac', Join::WITH, 'f1.aircraft = ac.id')
            ->where('ac.tail = :tail')
            ->setParameter('tail', $filter->getTail())
            ->andWhere('f1.aircraft = f2.aircraft')
            ->andWhere('f2.landing < f1.takeoff')
            ->andWhere('NOT EXISTS('.$builder->getDQL().')')
            ->orderBy('f2.landing', Criteria::ASC)
            ->setMaxResults(1000)
        ;

        if (null !== $filter->getFromDate()) {
            $query->andWhere('f2.landing >= :fromdate')
                ->setParameter('fromdate', $filter->getFromDate())
            ;
        }

        if (null !== $filter->getToDate()) {
            $query->andWhere('f1.takeoff <= :toDate')
                ->setParameter('toDate', $filter->getToDate())
            ;
        }

        /** @var HistoryLocation[] */
        $history = [];

        foreach ($query->getQuery()->getResult() as $row) {
            $data = new HistoryLocation();
            $data->setAirportId($row['id']);
            $data->setCodeIata($row['codeIata']);
            $data->setCodeIcao($row['codeIcao']);
            $data->setLanding($row['landing']);
            $data->setOffload($row['offload']);
            $data->setLoad($row['load']);
            $data->setTakeoff($row['takeoff']);

            $history[] = $data;
        }

        return $history;
    }
}
