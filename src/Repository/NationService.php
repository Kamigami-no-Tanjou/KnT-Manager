<?php

namespace App\Repository;

use App\Entity\Nation;
use App\Utils\DateTimeService;
use App\Utils\PDOService;
use PDO;

class NationService implements IGetService
{
    private PDO $context;
    private DateTimeService $dateTimeService;
    private CalendarService $calendarService;

    public function __construct(PDOService $contextSrc,
                                DateTimeService $dateTimeService,
                                CalendarService $calendarService
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->dateTimeService = $dateTimeService;
        $this->calendarService = $calendarService;
    }

    public function getById(?int $id): ?Nation
    {
        if ($id == null) return null;

        $nation = new Nation();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Calendar AS calendarId,
                        FoundationDate AS foundationDate,
                        IFNULL(DestructionDate, 'N/A') AS destructionDate,
                        Description AS description
                   FROM Nations
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        if (count($values) < 1) {
            return null;
        }

        $nation->setId($values[0]['id']);
        $nation->setName($values[0]['name']);
        $nation->setCalendar($this->calendarService->getById($values[0]['calendarId']));
        $nation->setFoundationDate(
            $this->dateTimeService->getDateTime($values[0]['foundationDate'])
        );
        $nation->setDestructionDate(
            $this->dateTimeService->getDateTime($values[0]['destructionDate'])
        );
        $nation->setDescription($values[0]['description']);

        return $nation;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Calendar AS calendarId,
                        FoundationDate AS foundationDate,
                        IFNULL(DestructionDate, 'N/A') AS destructionDate,
                        Description AS description
                   FROM Nations
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
                        Calendar AS calendarId,
                        FoundationDate AS foundationDate,
                        IFNULL(DestructionDate, 'N/A') AS destructionDate,
                        Description AS description
                   FROM Nations"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function simpleSearch(string $research): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        DISTINCT (NTN.ID) AS id,
                        NTN.Name AS name,
                        NTN.Calendar AS calendarId,
                        NTN.FoundationDate AS foundationDate,
                        IFNULL(NTN.DestructionDate, 'N/A') AS destructionDate,
                        NTN.Description AS description
                   FROM Nations NTN
                   INNER JOIN Calendars CDR ON NTN.Calendar = CDR.ID
                   LEFT JOIN NationsLeaders NTL ON NTN.ID = NTL.Nation
                   LEFT JOIN Characs CRC ON NTL.Leader = CRC.ID
                   WHERE
                       CRC.LastNames LIKE :search
                       OR CRC.FirstNames LIKE :search
                       OR NTN.Name LIKE :search
                       OR CDR.Name LIKE :search
                   LIMIT 6"
        );
        $statement->execute(['search' => $research]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function buildInstances(array $fetchedValues): array
    {
        $nations = array();
        foreach($fetchedValues as $row) {
            $nation = new Nation();
            $nation->setId($row['id']);
            $nation->setName($row['name']);
            $nation->setCalendar($this->calendarService->getById($row['calendarId']));
            $nation->setFoundationDate(
                $this->dateTimeService->getDateTime($row['foundationDate'])
            );
            $nation->setDestructionDate(
                $this->dateTimeService->getDateTime($row['destructionDate'])
            );
            $nation->setDescription($row['description']);
            $nations[] = $nation;
        }

        return $nations;
    }
}