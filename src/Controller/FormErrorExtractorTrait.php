<?php

namespace App\Controller;

trait FormErrorExtractorTrait
{
    /**
     * Extracts validation errors from a Symfony form.
     *
     * This method retrieves all validation errors from the given form, including:
     * - Field-specific errors (grouped by field name).
     * - Global form errors (stored under the 'global' key).
     *
     * @param FormInterface $form The form instance to extract errors from.
     * @return array An associative array containing field-specific and global errors.
     */
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
