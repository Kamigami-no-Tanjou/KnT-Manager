<?php

namespace App\Repository;

use App\Entity\Calendar;
use App\Utils\PDOService;
use DateTime;
use PDO;

class CalendarService implements IGetService
{
    private PDO $context;

    public function __construct(PDOService $contextSrc) {
        $this->context = $contextSrc->GetPDO();
    }

    public function getById(int $id): Calendar
    {
        $calendar = new Calendar();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        ReferenceDate AS referenceDate,
                        NegativeYears AS negativeYears
                   FROM Calendars
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $calendar->setId($values[0]['id']);
        $calendar->setName($values[0]['name']);
        $calendar->setReferenceDate(new DateTime($values[0]['referenceDate']));
        $calendar->setNegativeYears($values[0]['negativeYears']);

        return $calendar;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        ReferenceDate AS referenceDate,
                        NegativeYears AS negativeYears
                   FROM Calendars
                   LIMIT :amt"
        );
        $statement->execute(['amt' => $amt]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getAll(): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        ReferenceDate AS referenceDate,
                        NegativeYears AS negativeYears
                   FROM Calendars"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $calendars = array();
        foreach($fetchedValues as $row) {
            $calendar = new Calendar();
            $calendar->setId($row['id']);
            $calendar->setName($row['name']);
            $calendar->setReferenceDate(new DateTime($row['referenceDate']));
            $calendar->setNegativeYears($row['negativeYears']);
            $calendars[] = $calendar;
        }

        return $calendars;
    }
}