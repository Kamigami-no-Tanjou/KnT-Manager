<?php

namespace App\Repository;

use App\Entity\Charac;
use App\Entity\CharacEvent;
use App\Utils\PDOService;
use DateTime;
use PDO;

class CharacEventService implements IGetService
{
    private PDO $context;
    private CharacService $characService;

    public function __construct(PDOService $contextSrc,
                                CharacService $characService,
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->characService = $characService;
    }

    public function getById(int $id): ?CharacEvent
    {
        $characEvent = new CharacEvent();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        StartingDate AS startingDate,
                        EndingDate AS endingDate,
                        Charac AS characId,
                        Description AS description
                   FROM CharacEvents
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        if (count($values) < 1) {
            return null;
        }

        $characEvent->setId($values[0]['id']);
        $characEvent->setStartingDate(new DateTime($values[0]['startingDate']));
        $characEvent->setEndingDate(new DateTime($values[0]['endingDate']));
        $characEvent->setCharac($this->characService->getById($values[0]['characId']));
        $characEvent->setDescription($values[0]['description']);

        return $characEvent;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        StartingDate AS startingDate,
                        EndingDate AS endingDate,
                        Charac AS characId,
                        Description AS description
                   FROM CharacEvents
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
                        StartingDate AS startingDate,
                        EndingDate AS endingDate,
                        Charac AS characId,
                        Description AS description
                   FROM CharacEvents"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByPeriod(DateTime $start, DateTime $end): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        StartingDate AS startingDate,
                        EndingDate AS endingDate,
                        Charac AS characId,
                        Description AS description
                   FROM CharacEvents
                   WHERE StartingDate >= :startingDate
                   AND EndingDate < :endingDate
                   ORDER BY StartingDate"
        );
        $statement->execute([
            'startingDate' => $start->format("Y-m-d"),
            'endingDate' => $end->format("Y-m-d")
        ]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByCharac(Charac $charac): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        StartingDate AS startingDate,
                        EndingDate AS endingDate,
                        Charac AS characId,
                        Description AS description
                    FROM CharacEvents
                    WHERE Charac = :characId"
        );
        $statement->execute(['characId' => $charac->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $characEvents = array();
        foreach($fetchedValues as $row) {
            $characEvent = new CharacEvent();
            $characEvent->setId($row['id']);
            $characEvent->setStartingDate(new DateTime($row['startingDate']));
            $characEvent->setEndingDate(new DateTime($row['endingDate']));
            $characEvent->setCharac($this->characService->getById($row['characId']));
            $characEvent->setDescription($row['description']);
            $characEvents[] = $characEvent;
        }

        return $characEvents;
    }
}