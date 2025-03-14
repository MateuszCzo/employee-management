<?php

namespace App\Service;

use App\Entity\DailyWorkTimeType;
use App\Dto\WorkSummary;

interface WorkSummaryServiceInterface
{
    /**
     * Calculates the work summary based on daily work time entries.
     *
     * This method computes the total hours worked, standard hours, overtime hours,
     * and corresponding salary calculations for an employee.
     *
     * - The total hours are calculated by summing up the rounded worked hours for each entry.
     * - Standard hours are capped at the monthly norm (Constants::MONTHLY_HOURS_NORM).
     * - Overtime hours are any hours exceeding the monthly norm.
     * - Standard pay is calculated based on the standard hours and hourly salary.
     * - Overtime pay is calculated with an overtime multiplier.
     *
     * @param DailyWorkTimeType[] $dailyWorkTimeType Array of daily work time entries.
     * @return WorkSummary The computed summary containing total hours, standard hours, overtime hours, and pay details.
     */
    public function calculateWorkSummary(array $dailyWorkTimeType): WorkSummary;
}