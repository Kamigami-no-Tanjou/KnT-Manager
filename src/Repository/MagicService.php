<?php

namespace App\Repository;

use App\Entity\Elem;
use App\Entity\Magic;
use App\Utils\PDOService;
use PDO;

class MagicService implements IGetService
{
    private PDO $context;
    private ElemService $elemService;

    public function __construct(PDOService $contextSrc,
                                ElemService $elemService,
    ) {
        $this->context = $contextSrc->GetPDO();
        $this->elemService = $elemService;
    }

    public function getById(int $id): Magic
    {
        $magic = new Magic();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Description AS description
                   FROM Magics
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $magic->setId($values[0]['id']);
        $magic->setName($values[0]['name']);
        $magic->setDescription($values[0]['description']);
        $magic->setElems($this->getElements($magic->getId()));

        return $magic;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Description AS description
                   FROM Magics
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
                        Description AS description
                   FROM Magics"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getByElem(Elem $elem): array {
        $statement = $this->context->prepare(
            "SELECT
                        MGC.ID AS id,
                        MGC.Name AS name,
                        MGC.Description AS description
                    FROM Magics MGC
                    INNER JOIN LINK_ElemsMagics LEM ON MGC.ID = LEM.Magic
                    WHERE LEM.Elem = :elemId"
        );
        $statement->execute(['elemId' => $elem->getId()]);
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    public function getUsersCount(Magic $magic): int {
        $statement = $this->context->prepare(
            "SELECT COUNT(DISTINCT(Charac)) AS `count`
                    FROM LINK_CharacsMagics
                    WHERE Magic = :magicId"
        );
        $statement->execute(['magicId' => $magic->getId()]);
        $count = $statement->fetchAll();

        return $count[0]['count'];
    }

    function buildInstances(array $fetchedValues): array
    {
        $magics = array();
        foreach($fetchedValues as $row) {
            $magic = new Magic();
            $magic->setId($row['id']);
            $magic->setName($row['name']);
            $magic->setDescription($row['description']);
            $magic->setElems($this->getElements($magic->getId()));
            $magics[] = $magic;
        }

        return $magics;
    }

    private function getElements(int $magicId): array {
        $statement = $this->context->prepare(
            "SELECT
                        Elem AS elemId
                    FROM LINK_ElemsMagics
                    WHERE Magic = :id"
        );
        $statement->execute(['id' => $magicId]);
        $elemIds = $statement->fetchAll();

        $elems = array();
        foreach($elemIds as $id) {
            $elems[] = $this->elemService->getById($id[0]);
        }

        return $elems;
    }
}