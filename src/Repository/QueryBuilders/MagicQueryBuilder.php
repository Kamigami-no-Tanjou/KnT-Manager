<?php

namespace App\Repository\QueryBuilders;

use App\Repository\MagicService;
use App\Utils\PDOService;
use PDO;

class MagicQueryBuilder implements IQueryBuilder
{
    private PDO $context;
    private MagicService $magicService;

    private int $tokenIndex;
    private array $tokenArray;

    private ?string $whereConditions;
    private string $query =
        "SELECT
             DISTINCT(Magics.ID) AS id,
             Magics.Name AS name,
             Description AS description
         FROM Magics
         LEFT JOIN LINK_ElemsMagics ON Magics.ID = LINK_ElemsMagics.Magic
         LEFT JOIN Elems ON LINK_ElemsMagics.Elem = Elems.ID";

    public function __construct(PDOService $contextSrc,
                                MagicService $magicService) {
        $this->context = $contextSrc->GetPDO();
        $this->magicService = $magicService;

        $this->whereConditions = null; // Just to be sure we are in a proper state before starting
        $this->tokenIndex = 1;
        $this->tokenArray = array();
    }

    public function where(string $condition): MagicQueryBuilder
    {
        if ($this->whereConditions == null) {
            $this->whereConditions = " WHERE " . $condition;
        } else {
            $this->whereConditions .= " AND " . $condition;
        }

        return $this;
    }

    public function addKeyWords(array $tokens): IQueryBuilder
    {
        $condition = "";

        foreach ($tokens as $token) {
            $requestToken = $this->createRequestToken($token);
            $condition = $this->addTokenCondition($condition, "Magics.Name LIKE " . $requestToken);
        }

        $condition .= ")";
        $this->where($condition);

        return $this;
    }

    public function getMatches(array $variables): array
    {
        $queryString = $this->query;
        if ($this->whereConditions != null) {
            $queryString .= $this->whereConditions;
        }
        $statement = $this->context->prepare($queryString);
        $statement->execute(array_merge($variables, $this->tokenArray));
        $values = $statement->fetchAll();

        return $this->magicService->buildInstances($values);
    }

    private function addTokenCondition(string $currentCondition, string $conditionToAdd): string {
        if ($currentCondition == "") {
            $condition = "(" . $conditionToAdd;
        } else {
            $condition = $currentCondition . " OR " . $conditionToAdd;
        }

        return $condition;
    }

    private function createRequestToken(string $token): string {
        $tokenValue = "%" . $token . "%";
        $requestToken = ":token" . $this->tokenIndex;

        $this->tokenArray["token" . $this->tokenIndex] = $tokenValue;
        $this->tokenIndex++;

        return $requestToken;
    }
}