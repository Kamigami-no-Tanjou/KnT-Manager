<?php

namespace App\Utils;

use DateTime;
use PDO;

class FunctionContainer
{
    private PDO $context;

    public function __construct(PDO $pdo) {
        $this->context = $pdo;
    }
    public function convertToCalendar(DateTime $date, int $calendarId) {
        $statement = $this->context->prepare("SELECT YearFromDate(CONCAT(:y, '-01-01'), :c) AS year");
        $statement->execute([
            'y' => $date->format('Y'),
            'c' => $calendarId
        ]);
        $year = $statement->fetchAll()[0]['year'];

        if ($year < 0 && $year > -1000) {
            if ($year > -100) {
                $stringYear = "-00" . abs($year);
            } else {
                $stringYear = "-0" . abs($year);
            }
        } elseif ($year < 1000 && $year > 0) {
            if ($year < 100) {
                $stringYear = "00" . $year;
            } else {
                $stringYear = "0" . $year;
            }
        } elseif ($year == 0) {
            $stringYear = "0000";
        } else {
            $stringYear = "" . $year;
        }

        return new DateTime($stringYear . $date->format('-m-d'));
    }
}