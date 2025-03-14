<?php

namespace App\Service\Impl;

use App\Constants\Constants;
use App\Dto\WorkSummary;
use App\Service\WorkSummaryServiceInterface;
use App\Service\WorkTimeRounderTrait;

class WorkSummaryService implements WorkSummaryServiceInterface
{
    use WorkTimeRounderTrait;

    /**
     * @inheritDoc
     */
    public function calculateWorkSummary(array $dailyWorkTimeType): WorkSummary
    {
        $totalHours = 0;
        foreach ($dailyWorkTimeType as $entry) {
            $workedTime = $entry->getEndDateTime()
                ->diff($entry->getStartDateTime());
            $totalHours += $this->roundHours($workedTime);
        }

        $standardHours = min($totalHours, Constants::MONTHLY_HOURS_NORM);
        $overtimeHours = max(0, $totalHours - Constants::MONTHLY_HOURS_NORM);

        $standardPay = $standardHours * Constants::HOUR_SALARY;
        $overtimePay = $overtimeHours * Constants::HOUR_SALARY * Constants::OVERTIME_MULTIPLIER;

        return new WorkSummary(
            $totalHours,
            $standardHours,
            $overtimeHours,
            $standardPay,
            $overtimePay
        );
    }
}
