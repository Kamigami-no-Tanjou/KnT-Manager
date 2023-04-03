<?php

namespace App\Repository;

use App\Entity\_Class;
use App\Entity\Charac;
use App\Entity\Nation;
use App\Utils\DateTimeService;
use App\Utils\PDOService;
use DateTime;
use PDO;

class CharacService implements IGetService
{
    private PDO $context;
    private CalendarService $calendarService;
    private ClassService $classService;
    private NationService $nationService;
    private MagicService $magicService;
    private DateTimeService $dateTimeService;

    public function __construct(PDOService $contextSrc,
                                CalendarService $calendarService,
                                ClassService $classService,
                                NationService $nationService,
                                MagicService $magicService,
                                DateTimeService $dateTimeService
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->calendarService = $calendarService;
        $this->classService = $classService;
        $this->nationService = $nationService;
        $this->magicService = $magicService;
        $this->dateTimeService = $dateTimeService;
    }

    public function getById(int $id): Charac
    {
        $charac = new Charac();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        LastNames AS lastNames,
                        FirstNames AS firstNames,
                        Portrait AS portrait,
                        Calendar AS calendarId,
                        Birthdate AS birthdate,
                        IFNULL(Deathdate, 'N/A') AS deathdate,
                        Class AS classId,
                        Origin AS originId
                   FROM Characs
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $charac->setId($values[0]['id']);
        $charac->setLastNames($values[0]['lastNames']);
        $charac->setFirstNames($values[0]['firstNames']);
        $charac->setPortrait($values[0]['portrait']);
        $charac->setCalendar($this->calendarService->getById($values[0]['calendarId']));
        $charac->setBirthdate(new DateTime($values[0]['birthdate']));
        $charac->setDeathdate(
            $this->dateTimeService->getDateTime($values[0]['deathdate'])
        );
        $charac->setClass($this->getClass($values[0]['classId']));
        $charac->setOrigin($this->nationService->getById($values[0]['originId']));
        $charac->setMagics($this->getMagics($charac->getId()));

        return $charac;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        LastNames AS lastNames,
                        FirstNames AS firstNames,
                        Portrait AS portrait,
                        Calendar AS calendarId,
                        Birthdate AS birthdate,
                        IFNULL(Deathdate, 'N/A') AS deathdate,
                        Class AS classId,
                        Origin AS originId
                   FROM Characs
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
                        LastNames AS lastNames,
                        FirstNames AS firstNames,
                        Portrait AS portrait,
                        Calendar AS calendarId,
                        Birthdate AS birthdate,
                        IFNULL(Deathdate, 'N/A') AS deathdate,
                        Class AS classId,
                        Origin AS originId
                   FROM Characs"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByClass(_Class $class): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        LastNames AS lastNames,
                        FirstNames AS firstNames,
                        Portrait AS portrait,
                        Calendar AS calendarId,
                        Birthdate AS birthdate,
                        IFNULL(Deathdate, 'N/A') AS deathdate,
                        Class AS classId,
                        Origin AS originId
                   FROM Characs
                   WHERE Class = :classId"
        );
        $statement->execute(['classId' => $class->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByNation(Nation $nation): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID as id,
                        LastNames AS lastNames,
                        FirstNames AS firstNames,
                        Portrait AS portrait,
                        Calendar AS calendarId,
                        Birthdate AS birthdate,
                        IFNULL(Deathdate, 'N/A') AS deathdate,
                        Class AS classId,
                        Origin AS originId
                   FROM Characs
                   WHERE Origin = :nationId"
        );
        $statement->execute(['nationId' => $nation->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getAllWithEvents(): array {
        $statement = $this->context->prepare(
            "SELECT
                        DISTINCT(CRC.ID) AS id,
                        CRC.LastNames AS lastNames,
                        CRC.FirstNames AS firstNames,
                        CRC.Portrait AS portrait,
                        CRC.Calendar AS calendarId,
                        CRC.Birthdate AS birthdate,
                        IFNULL(CRC.Deathdate, 'N/A') AS deathdate,
                        CRC.Class AS classId,
                        CRC.Origin AS originId
                   FROM Characs CRC
                   INNER JOIN CharacEvents CRE
                        ON CRC.ID = CRE.Charac"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getCharacFamily(Charac $charac): array {
        $statement = $this->context->prepare(
            "SELECT
                        CRC.ID AS id,
                        CRC.LastNames AS lastNames,
                        CRC.FirstNames AS firstNames,
                        CRC.Portrait AS portrait,
                        CRC.Calendar AS calendarId,
                        CRC.Birthdate AS birthdate,
                        IFNULL(CRC.Deathdate, 'N/A') AS deathdate,
                        CRC.Class AS classId,
                        CRC.Origin AS originId
                    FROM CharacsRelationships CRR
                    INNER JOIN Characs CRC ON CRR.TowardsCharac = CRC.ID
                    WHERE CRR.FamilyStatus IS NOT NULL
                    AND CRR.FromCharac = :characId"
        );
        $statement->execute(['characId' => $charac->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getAllIdAndNames(): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        LastNames AS lastNames,
                        FirstNames AS firstNames
                    FROM Characs
                    ORDER BY FirstNames"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        $characs = array();
        foreach($values as $row) {
            $charac = new Charac();
            $charac->setId($row['id']);
            $charac->setLastNames($row['lastNames']);
            $charac->setFirstNames($row['firstNames']);

            $characs[] = $charac;
        }

        return $characs;
    }

    function buildInstances(array $fetchedValues): array
    {
        $characs = array();
        foreach($fetchedValues as $row) {
            $charac = new Charac();
            $charac->setId($row['id']);
            $charac->setLastNames($row['lastNames']);
            $charac->setFirstNames($row['firstNames']);
            $charac->setPortrait($row['portrait']);
            $charac->setCalendar($this->calendarService->getById($row['calendarId']));
            $charac->setBirthdate(new DateTime($row['birthdate']));
            $charac->setDeathdate(
                $this->dateTimeService->getDateTime($row['deathdate'])
            );
            $charac->setClass($this->getClass($row['classId']));
            $charac->setOrigin($this->nationService->getById($row['originId']));
            $charac->setMagics($this->getMagics($charac->getId()));
            $characs[] = $charac;
        }

        return $characs;
    }

    private function getClass(?int $id): ?_Class {
        if ($id == null) {
            return null;
        } else {
            return $this->classService->getById($id);
        }
    }

    private function getMagics(int $characId): array {
        $statement = $this->context->prepare(
            "SELECT
                        Magic AS magicId
                    FROM LINK_CharacsMagics
                    WHERE Charac = :id
                    ORDER BY `Rank`" // The order is important (otherwise the rank wouldn't be respected)!
        );
        $statement->execute(['id' => $characId]);
        $magicIds = $statement->fetchAll();

        $magics = array();
        foreach($magicIds as $id) {
            $magics[] = $this->magicService->getById($id[0]);
        }

        return $magics;
    }
}