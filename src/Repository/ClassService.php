<?php

namespace App\Repository;

use App\Entity\_Class;
use App\Utils\PDOService;
use PDO;

class ClassService implements IGetService
{
    private PDO $context;

    public function __construct(PDOService $contextSrc) {
        $this->context = $contextSrc->GetPDO();
    }
    public function getById(int $id): _Class {
        $class = new _Class();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name
                   FROM Classes
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $class->setId($values[0]['id']);
        $class->setName($values[0]['name']);

        return $class;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name
                    FROM Classes
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
                    FROM Classes"
        );
        $statement->execute();
        $values = $statement->fetchAll();

        return $this->buildInstances($values);
    }

    function buildInstances(array $fetchedValues): array
    {
        $classes = array();
        foreach($fetchedValues as $row) {
            $class = new _Class();
            $class->setId($row['id']);
            $class->setName($row['name']);
            $classes[] = $class;
        }

        return $classes;
    }
}