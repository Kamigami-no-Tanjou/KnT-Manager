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

    public function getById(int $id): Elem
    {
        $elem = new Elem();
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Illustration AS illustration
                   FROM Elems
                   WHERE ID = :id"
        );
        $statement->execute(['id' => $id]);
        $values = $statement->fetchAll();

        $elem->setId($values[0]['id']);
        $elem->setName($values[0]['name']);
        $elem->setIllustration(
            base64_encode(
                $values[0]['illustration']
            ));

        return $elem;
    }

    public function getAmount(int $amt): array
    {
        $statement = $this->context->prepare(
            "SELECT
                        ID AS id,
                        Name AS name,
                        Illustration AS illustration
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
                        Name AS name,
                        Illustration AS illustration
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
            $elem->setIllustration(
                base64_encode(
                    $row['illustration']
                ));
            $elems[] = $elem;
        }

        return $elems;
    }
}