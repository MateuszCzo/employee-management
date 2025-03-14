<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/employee', name: 'employee_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'response' => ['id' => $employee->getId()]
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
