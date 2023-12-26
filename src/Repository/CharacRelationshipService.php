<?php

namespace App\Repository;

use App\Entity\Charac;
use App\Entity\CharacRelationship;
use App\Entity\FamilyStatus;
use App\Utils\PDOService;
use PDO;

class CharacRelationshipService implements IGetService
{
    private PDO $context;
    private CharacService $characService;
    private FamilyStatusService $familyStatusService;

    public function __construct(PDOService $contextSrc,
                                CharacService $characService,
                                FamilyStatusService $familyStatusService
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->characService = $characService;
        $this->familyStatusService = $familyStatusService;
    }

    public function getById(int $id): ?CharacRelationship
    {
        $characRelationship = new CharacRelationship();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        FromCharac AS fromCharacId,
                        TowardsCharac AS towardsCharacId,
                        FamilyStatus AS familyStatus,
                        Appreciation AS appreciation,
                        History AS history
                   FROM CharacsRelationships
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        if (count($values) < 1) {
            return null;
        }

        $characRelationship->setId($values[0]['id']);
        $characRelationship->setFromCharac($this->characService->getById($values[0]['fromCharacId']));
        $characRelationship->setTowardsCharac($this->characService->getById($values[0]['towardsCharacId']));
        $characRelationship->setFamilyStatus($this->familyStatusService->nullableFrom($values[0]['familyStatus']));
        $characRelationship->setAppreciation($values[0]['appreciation']);
        $characRelationship->setHistory($values[0]['history']);

        return $characRelationship;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        FromCharac AS fromCharacId,
                        TowardsCharac AS towardsCharacId,
                        FamilyStatus AS familyStatus,
                        Appreciation AS appreciation,
                        History AS history
                   FROM CharacsRelationships
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
                        FromCharac AS fromCharacId,
                        TowardsCharac AS towardsCharacId,
                        FamilyStatus AS familyStatus,
                        Appreciation AS appreciation,
                        History AS history
                   FROM CharacsRelationships"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByCharac(Charac $charac): array {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        FromCharac AS fromCharacId,
                        TowardsCharac AS towardsCharacId,
                        FamilyStatus AS familyStatus,
                        Appreciation AS appreciation,
                        History AS history
                   FROM CharacsRelationships
                   WHERE FromCharac = :characId"
        );
        $statement->execute(['characId' => $charac->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $characRelationships = array();
        foreach($fetchedValues as $row) {
            $characRelationship = new CharacRelationship();
            $characRelationship->setId($row['id']);
            $characRelationship->setFromCharac($this->characService->getById($row['fromCharacId']));
            $characRelationship->setTowardsCharac($this->characService->getById($row['towardsCharacId']));
            $characRelationship->setFamilyStatus($this->familyStatusService->nullableFrom($row['familyStatus']));
            $characRelationship->setAppreciation($row['appreciation']);
            $characRelationship->setHistory($row['history']);
            $characRelationships[] = $characRelationship;
        }

        return $characRelationships;
    }
}