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
                        Nation AS nationId,
                        Content AS content
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
        $map->setContent(
            base64_encode(
                $values[0]['content']
            ));

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
                        Nation AS nationId,
                        Content AS content
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
                        Nation AS nationId,
                        Content AS content
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
                        Nation AS nationId,
                        Content AS content
                   FROM Maps
                   WHERE Nation = :nationId"
        );
        $statement->execute(['nationId' => $nation->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
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
            $map->setContent(
                base64_encode(
                    $row['content']
                ));
            $maps[] = $map;
        }

        return $maps;
    }
}