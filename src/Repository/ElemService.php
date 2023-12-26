<?php

namespace App\Repository;

use App\Entity\Elem;
use App\Utils\PDOService;
use PDO;

class ElemService implements IGetService
{
    private PDO $context;

    public function __construct(PDOService $contextSrc) {
        $this->context = $contextSrc->GetPDO();
    }

    public function getById(int $id): ?Elem
    {
        $elem = new Elem();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name
                   FROM Elems
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        if (count($values) < 1) {
            return null;
        }

        $elem->setId($values[0]['id']);
        $elem->setName($values[0]['name']);

        return $elem;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name
                   FROM Elems
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
                        Name AS name
                   FROM Elems"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $elems = array();
        foreach($fetchedValues as $row) {
            $elem = new Elem();
            $elem->setId($row['id']);
            $elem->setName($row['name']);
            $elems[] = $elem;
        }

        return $elems;
    }

    public function getIllustrationById(int $id): ?string {
        $statement = $this->context->prepare(
            "SELECT
                        Illustration AS illustration
                    FROM Elems
                    WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetchAll();

        if (count($row) < 1) {
            return null;
        }

        return $row[0]['illustration'];
    }
}