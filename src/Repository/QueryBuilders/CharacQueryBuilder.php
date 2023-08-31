<?php

namespace App\Repository\QueryBuilders;

use PDO;
use App\Utils\PDOService;
use App\Repository\CharacService;
use function Sodium\add;

class CharacQueryBuilder implements IQueryBuilder
{
    private PDO $context;
    private CharacService $characService;

    private int $tokenIndex;
    private array $tokenArray;
    private ?string $whereConditions;
    private string $query =
        "SELECT
             DISTINCT(Characs.ID) AS id,
             LastNames AS lastNames,
             FirstNames AS firstNames,
             Description AS description,
             Calendar AS calendarId,
             Birthdate AS birthdate,
             IFNULL(Deathdate, 'N/A') AS deathdate,
             MagicalPotential AS magicalPotential,
             Class AS classId,
             Sex AS sex,
             Gender AS gender,
             SexualOrientation AS sexualOrientation,
             Origin AS originId,
             
             -- Physical characteristics
             Height AS height,
             HairColour AS hairColour,
             EyeColour AS eyeColour,
             Appearance AS appearance,
             
             -- Taste
             FavoriteColour AS favoriteColour,
             ThingsLoved AS thingsLoved,
             ThingsHated AS thingsHated
         FROM Characs
         LEFT JOIN LINK_CharacsMagics ON Characs.ID = LINK_CharacsMagics.Charac
         LEFT JOIN NationsLeaders ON Characs.ID = NationsLeaders.Leader
         LEFT JOIN CharacsRelationships ON Characs.ID = CharacsRelationships.FromCharac";

    public function __construct(PDOService $contextSrc,
                                CharacService $characService) {
        $this->context = $contextSrc->GetPDO();
        $this->characService = $characService;

        $this->whereConditions = null; // Just to be sure we are in a proper state before starting
        $this->tokenIndex = 1;
        $this->tokenArray = array();
    }

    public function where(string $condition): CharacQueryBuilder
    {
        if ($this->whereConditions == null) {
            $this->whereConditions = " WHERE " . $condition;
        } else {
            $this->whereConditions .= " AND " . $condition;
        }

        return $this;
    }

    public function addKeyWords(array $tokens): CharacQueryBuilder
    {
        $condition = "";

        foreach ($tokens as $token) {
            $requestToken = $this->createRequestToken($token);
            $condition = $this->addTokenCondition($condition, "LastNames LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "FirstNames LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "SexualOrientation LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "HairColour LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "EyeColour LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "FavoriteColour LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "ThingsLoved LIKE " . $requestToken);
            $condition = $this->addTokenCondition($condition, "ThingsHated LIKE " . $requestToken);
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

        return $this->characService->buildInstances($values);
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