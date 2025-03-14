<?php

namespace App\Controller;

use App\Entity\DailyWorkTime;
use App\Form\DailyWorkTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyWorkTimeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/daily-work-time', name: 'daily_work_time_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dayWorkTime = new DailyWorkTime();
        $form = $this->createForm(DailyWorkTimeType::class, $dayWorkTime);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($dayWorkTime);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'response' => ['Daily work time created!'],
                ], 
                Response::HTTP_CREATED
            );
        }

        $errors = [];
        foreach ($form->all() as $fieldName => $formField) {
            foreach ($formField->getErrors() as $error) {
                $errors[$fieldName][] = $error->getMessage();
            }
        }
        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
