<?php

namespace App\Service;

use App\Entity\DailyWorkTimeType;
use App\Dto\WorkSummary;

interface WorkSummaryServiceInterface
{
    /**
     * @param DailyWorkTimeType[] $dailyWorkTimeType
     * @return WorkSummary
     */
    public function calculateWorkSummary(array $dailyWorkTimeType): WorkSummary;
}