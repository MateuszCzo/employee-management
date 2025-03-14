<?php

namespace App\Service;

use DateInterval;

trait WorkTimeRounderTrait
{
    /**
     * Rounds the given work time to the nearest half-hour.
     *
     * - If the minutes are less than 15, the hour remains unchanged.
     * - If the minutes are between 15 and 44, the hour is rounded up by 0.5.
     * - If the minutes are 45 or more, the hour is rounded up to the next whole hour.
     *
     * @param DateInterval $date The time interval to be rounded.
     * @return float The rounded number of hours.
     */
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
