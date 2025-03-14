<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\DailyWorkTimeRepository;
use App\Service\WorkSummaryServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WorkSummaryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DailyWorkTimeRepository $dailyWorkTimeRepository,
        private WorkSummaryServiceInterface $workSummaryService
    ) {}

    #[Route('/work-summary/{id}', name: 'work_summary', methods: ['GET', 'HEAD'])]
    public function getWorkSummary(?Employee $employee, Request $request): Response
    {
        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $requestDate = $request->query->get('date') ?? (new DateTime())->format('Y-m-d');

        $dateFrom = (new DateTime($requestDate))->setTime(0, 0, 0);
        $dateTo = null;

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestDate)) {
            $dateTo = (new DateTime($requestDate))
                ->setTime(23, 59, 59);
        } elseif (preg_match('/^\d{4}-\d{2}$/', $requestDate)) {
            $dateTo = (new DateTime($requestDate))
                ->modify('last day of this month')
                ->setTime(23, 59, 59);
        } else {
            return $this->json(['error' => 'Invalid date format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $dailyWorkTimes = $this->dailyWorkTimeRepository->findByEmployeeAndDateRange($employee, $dateFrom, $dateTo);

        $workSummary = $this->workSummaryService->calculateWorkSummary($dailyWorkTimes);

        return $this->json($workSummary, JsonResponse::HTTP_OK);
    }
}
