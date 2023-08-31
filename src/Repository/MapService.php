<?php

namespace App\Repository;

use App\Entity\Map;
use App\Entity\Nation;
use App\Utils\DateTimeService;
use App\Utils\PDOService;
use PDO;

class MapService implements IGetService
{
    private PDO $context;
    private NationService $nationService;
    private DateTimeService $dateTimeService;

    public function __construct(PDOService $contextSrc,
                                NationService $nationService,
                                DateTimeService $dateTimeService
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->nationService = $nationService;
        $this->dateTimeService = $dateTimeService;
    }

    public function getById(int $id): object
    {
        $map = new Map();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        IFNULL(EstablishmentDate, 'N/A') AS establishmentDate,
                        IFNULL(ExpiryDate, 'N/A') AS expiryDate,
                        Nation AS nationId
                   FROM Maps
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $map->setId($values[0]['id']);
        $map->setName($values[0]['name']);
        $map->setEstablishmentDate(
            $this->dateTimeService->getDateTime($values[0]['establishmentDate'])
        );
        $map->setExpiryDate(
            $this->dateTimeService->getDateTime($values[0]['expiryDate'])
        );
        $map->setNation($this->nationService->getById($values[0]['nationId']));

        return $map;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        IFNULL(EstablishmentDate, 'N/A') AS establishmentDate,
                        IFNULL(ExpiryDate, 'N/A') AS expiryDate,
                        Nation AS nationId
                   FROM Maps
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
                        IFNULL(EstablishmentDate, 'N/A') AS establishmentDate,
                        IFNULL(ExpiryDate, 'N/A') AS expiryDate,
                        Nation AS nationId
                   FROM Maps"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByNation(Nation $nation): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        IFNULL(EstablishmentDate, 'N/A') AS establishmentDate,
                        IFNULL(ExpiryDate, 'N/A') AS expiryDate,
                        Nation AS nationId
                   FROM Maps
                   WHERE Nation = :nationId"
        );
        $statement->execute(['nationId' => $nation->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function simpleSearch(string $research): array {
        $statement = $this->context->prepare(
            "SELECT
                        DISTINCT (MAP.ID) AS id,
                        MAP.Name AS name,
                        IFNULL(MAP.EstablishmentDate, 'N/A') AS establishmentDate,
                        IFNULL(MAP.ExpiryDate, 'N/A') AS expiryDate,
                        MAP.Nation AS nationId
                   FROM Maps MAP
                   INNER JOIN Nations NTN ON MAP.Nation = NTN.ID
                   WHERE
                        DATE_FORMAT(MAP.EstablishmentDate, '%d-%m-%Y') LIKE :search
                        OR DATE_FORMAT(MAP.ExpiryDate, '%d-%m-%Y') LIKE :search
                        OR NTN.Name LIKE :search
                   LIMIT 6"
        );
        $statement->execute(['search' => $research]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function buildInstances(array $fetchedValues): array
    {
        $maps = array();
        foreach($fetchedValues as $row) {
            $map = new Map();
            $map->setId($row['id']);
            $map->setName($row['name']);
            $map->setEstablishmentDate(
                $this->dateTimeService->getDateTime($row['establishmentDate'])
            );
            $map->setExpiryDate(
                $this->dateTimeService->getDateTime($row['expiryDate'])
            );
            $map->setNation($this->nationService->getById($row['nationId']));
            $maps[] = $map;
        }

        return $maps;
    }

    public function getMapById(int $id): string {
        $statement = $this->context->prepare(
            "SELECT
                        Content AS content
                   FROM Maps
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $map = $statement->fetchAll();

        return $map[0]['content'];
    }
}