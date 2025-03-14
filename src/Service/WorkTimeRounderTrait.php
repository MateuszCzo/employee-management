<?php

namespace App\Service;

use DateInterval;

trait WorkTimeRounderTrait
{
    protected function roundHours(DateInterval $date): float
    {
        if ($date->i < 15) {
            return $date->h;
        } elseif ($date->i < 45) {
            return $date->h + 0.5;
        } else {
            return $date->h + 1;
        }
    }
}
