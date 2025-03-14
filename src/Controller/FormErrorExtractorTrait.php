<?php

namespace App\Controller;

trait FormErrorExtractorTrait
{
    protected function extractFormErrors($form): array
    {
        $errors = [];

        foreach ($form->all() as $fieldName => $formField) {
            foreach ($formField->getErrors() as $error) {
                $errors[$fieldName][] = $error->getMessage();
            }
        }
        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $error->getMessage();
        }

        return $errors;
    }
}
