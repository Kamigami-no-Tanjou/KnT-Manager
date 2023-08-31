<?php

namespace App\Repository\QueryBuilders;

use App\Repository\NationService;
use App\Utils\PDOService;
use PDO;

class NationQueryBuilder implements IQueryBuilder
{
    private PDO $context;
    private NationService $nationService;

    private int $tokenIndex;
    private array $tokenArray;

    private ?string $whereConditions;
    private string $query =
        "SELECT
             DISTINCT(ID) AS id,
             Name AS name,
             Calendar AS calendarId,
             FoundationDate AS foundationDate,
             IFNULL(DestructionDate, 'N/A') AS destructionDate,
             Description AS description
         FROM Nations";

    public function __construct(PDOService $contextSrc,
                                NationService $nationService) {
        $this->context = $contextSrc->GetPDO();
        $this->nationService = $nationService;

        $this->whereConditions = null; // Just to be sure we are in a proper state.
        $this->tokenIndex = 1;
        $this->tokenArray = array();
    }

    public function where(string $condition): IQueryBuilder
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
            $condition = $this->addTokenCondition($condition, "Name LIKE " . $requestToken);
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

        return $this->nationService->buildInstances($values);
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