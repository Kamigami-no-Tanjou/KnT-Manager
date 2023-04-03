<?php

namespace App\Utils;

use DateTime;

class DateTimeService
{
    public function getDateTime(string $value): ?DateTime {
        if ($value == "N/A") {
            return null;
        }

        return new DateTime($value);
    }

    public function parseDateTime(string $value): DateTime {
        $values = explode('-', $value);

        $year = $values[0];
        if (count($values) > 1) {
            $month = $values[1];
        } else {
            $month = '01';
        }
        if (count($values) > 2) {
            $day = $values[2];
        } else {
            $day = '01';
        }

        return new DateTime($year . '-' . $month . '-' . $day);
    }

    public function computeYearsAge(DateTime $firstDate, DateTime $secondDate): int {
        $diff = $firstDate->diff($secondDate);

        return $diff->y;
    }
}