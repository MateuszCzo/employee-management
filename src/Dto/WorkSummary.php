<?php

namespace App\Dto;

use JsonSerializable;

class WorkSummary implements JsonSerializable
{
    public function __construct(
        private float $totalHours,
        private float $standardHours,
        private float $overtimeHours,
        private float $standardPay,
        private float $overtimePay
    ) {}

    public function getTotalHours(): float
    {
        return $this->totalHours;
    }

    public function getStandardHours(): float
    {
        return $this->standardHours;
    }

    public function getOvertimeHours(): float
    {
        return $this->overtimeHours;
    }

    public function getStandardPay(): float
    {
        return $this->standardPay;
    }

    public function getOvertimePay(): float
    {
        return $this->overtimePay;
    }

    public function getTotalPay(): float
    {
        return $this->standardPay + $this->overtimePay;
    }

    public function jsonSerialize(): array
    {
        return [
            'totalHours' => $this->totalHours,
            'standardHours' => $this->standardHours,
            'overtimeHours' => $this->overtimeHours,
            'standardPay' => $this->standardPay,
            'overtimePay' => $this->overtimePay,
            'totalPay' => $this->getTotalPay()
        ];
    }
}
