<?php

namespace App\Repository;

use App\Entity\Nation;
use App\Entity\NationLeader;
use App\Utils\DateTimeService;
use App\Utils\PDOService;
use DateTime;
use PDO;

class NationLeaderService implements IGetService
{
    private PDO $context;
    private NationService $nationService;
    private CharacService $characService;
    private DateTimeService $dateTimeService;

    public function __construct(PDOService $contextSrc,
                                NationService $nationService,
                                CharacService $characService,
                                DateTimeService $dateTimeService
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->nationService = $nationService;
        $this->characService = $characService;
        $this->dateTimeService = $dateTimeService;
    }

    public function getById(int $id): NationLeader
    {
        $nationLeader = new NationLeader();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Leader AS leaderId,
                        Nation AS nationId,
                        LeadStartDate AS leadStartDate,
                        IFNULL(LeadEndDate, 'N/A') AS leadEndDate
                   FROM NationsLeaders
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $nationLeader->setId($values[0]['id']);
        $nationLeader->setLeader($this->characService->getById($values[0]['leaderId']));
        $nationLeader->setNation($this->nationService->getById($values[0]['nationId']));
        $nationLeader->setLeadStartDate(new DateTime($values[0]['leadStartDate']));
        $nationLeader->setLeadEndDate(
            $this->dateTimeService->getDateTime($values[0]['leadEndDate'])
        );

        return $nationLeader;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Leader AS leaderId,
                        Nation AS nationId,
                        LeadStartDate AS leadStartDate,
                        IFNULL(LeadEndDate, 'N/A') AS leadEndDate
                   FROM NationsLeaders
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
                        Leader AS leaderId,
                        Nation AS nationId,
                        LeadStartDate AS leadStartDate,
                        IFNULL(LeadEndDate, 'N/A') AS leadEndDate
                   FROM NationsLeaders"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByNation(Nation $nation) {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Leader AS leaderId,
                        Nation AS nationId,
                        LeadStartDate AS leadStartDate,
                        IFNULL(LeadEndDate, 'N/A') AS leadEndDate
                   FROM NationsLeaders
                   WHERE Nation = :nationId"
        );
        $statement->execute(['nationId' => $nation->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $nationLeaders = array();
        foreach($fetchedValues as $row) {
            $nationLeader = new NationLeader();
            $nationLeader->setId($row['id']);
            $nationLeader->setLeader($this->characService->getById($row['leaderId']));
            $nationLeader->setNation($this->nationService->getById($row['nationId']));
            $nationLeader->setLeadStartDate(new DateTime($row['leadStartDate']));
            $nationLeader->setLeadEndDate(new DateTime($row['leadEndDate']));
            $nationLeader->setLeadEndDate(
                $this->dateTimeService->getDateTime($row['leadEndDate'])
            );
            $nationLeaders[] = $nationLeader;
        }

        return $nationLeaders;
    }
}