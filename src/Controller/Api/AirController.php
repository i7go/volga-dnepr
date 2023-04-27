<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Air\HistoryLocation;
use App\Entity\Air\HistoryLocationFilter;
use App\Factory\XmlResponseFactory;
use App\Model\Air\HistoryLocationCollectionModel;
use Nelmio\ApiDocBundle\Annotation as API;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * История нахождения ВС в тех или иных аэропортах.
 *
 * @OA\Tag(name="История нахождения ВС в тех или иных аэропортах")
 */
#[Route('/api/aircraft', name: 'api.aircraft')]
class AirController extends AbstractController
{
    private const DATETIME_FORMAT = 'Y-m-d\TH:i:s';

    public function __construct(
        private XmlResponseFactory $xmlResponseFactory,
    ) {
    }

    /**
     * Список истории нахождения ВС в тех или иных аэропортах.
     *
     * @OA\Parameter(name="tail", in="query", @OA\Schema(type="string"), example="TEST-001", description="Бортовой номер воздушного судна")
     * @OA\Parameter(name="from_date", in="query", @OA\Schema(type="string"), example="2023-01-01T22:00", description="Начало периода")
     * @OA\Parameter(name="to_date", in="query", @OA\Schema(type="string"), example="2023-05-01T00:00", description="Конец периода")
     * @OA\Parameter(name="format", in="query", @OA\Schema(type="string"), example="json", description="Формат вывода данных: json или xml")
     *
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Список истории нахождения ВС в тех или иных аэропортах",
     *
     *     @OA\JsonContent(
     *         type="array",
     *
     *         @OA\Items(ref=@API\Model(type=HistoryLocation::class, groups={"apiView"}))
     *     )
     * )
     */
    #[Route('/airport_schedule', name: '.airport_schedule', methods: ['GET'])]
    public function getListAction(
        HistoryLocationFilter $filter,
        HistoryLocationCollectionModel $model
    ): Response {
        if (!$model->validateFilter($filter)) {
            $error = $model->getValidationError();
            throw new BadRequestHttpException($error);
        }

        $history = $model->findCollectionAllHistoryLocations($filter);
        $context = [
            AbstractNormalizer::GROUPS => ['apiView'],
            DateTimeNormalizer::FORMAT_KEY => self::DATETIME_FORMAT,
        ];

        switch ($filter->getFormat()) {
            case 'json':
                return $this->json($history, Response::HTTP_OK, [], $context);
            case 'xml':
                return $this->xmlResponseFactory->create($history, 'history', Response::HTTP_OK, [], $context);
        }

        throw new BadRequestHttpException("Unknown format '{$filter->getFormat()}'");
    }
}
